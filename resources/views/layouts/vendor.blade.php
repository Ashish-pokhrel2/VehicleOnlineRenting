<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vendor Panel - VehicleRent</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 text-gray-900">

@php
    $vendorUser = auth()->user();
    $unreadNotificationCount = $vendorUser?->unreadNotifications()->count() ?? 0;
    $latestNotifications = $vendorUser?->notifications()->latest()->take(5)->get() ?? collect();
@endphp

<div class="min-h-screen">

    <!-- Top Navbar -->
    <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 sm:px-8">
        <div class="flex items-center gap-3">
            <button type="button" id="mobileMenuButton"
                    class="lg:hidden flex items-center justify-center w-10 h-10 rounded-xl bg-gray-100 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <img src="{{ asset('images/logo/logo.png') }}" alt="VehicleRent" class="h-10 w-auto">
        </div>

        <div class="flex items-center gap-3 sm:gap-4">

            <!-- Notification Bell -->
            <div class="relative">
                <button type="button" id="notificationButton"
                        class="relative flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-6 h-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 01-6 0m6 0H9" />
                    </svg>

                    @if($unreadNotificationCount > 0)
                        <span class="absolute -top-1 -right-1 min-w-[20px] h-5 px-1 flex items-center justify-center rounded-full bg-red-600 text-white text-xs font-bold">
                            {{ $unreadNotificationCount > 9 ? '9+' : $unreadNotificationCount }}
                        </span>
                    @endif
                </button>

                <!-- Notification Dropdown -->
                <div id="notificationDropdown"
                     class="hidden absolute right-0 mt-3 w-[320px] sm:w-96 bg-white rounded-2xl shadow-xl border border-gray-200 z-50 overflow-hidden">

                    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Notifications</h3>
                            <p class="text-xs text-gray-500">
                                {{ $unreadNotificationCount }} unread notification{{ $unreadNotificationCount === 1 ? '' : 's' }}
                            </p>
                        </div>

                        @if($unreadNotificationCount > 0)
                            <form method="POST" action="{{ route('vendor.notifications.readAll') }}">
                                @csrf
                                <button type="submit" class="text-xs font-semibold text-blue-600 hover:text-blue-800">
                                    Mark all read
                                </button>
                            </form>
                        @endif
                    </div>

                    <div class="max-h-96 overflow-y-auto">
                        @forelse($latestNotifications as $notification)
                            @php
                                $data = $notification->data ?? [];
                                $type = $data['type'] ?? 'notification';
                                $message = $data['message'] ?? 'New notification received.';
                                $vehicleName = $data['vehicle_name'] ?? 'Vehicle';
                                $customerName = $data['customer_name'] ?? 'Customer';

                                $badgeClass = match ($type) {
                                    'booking_created' => 'bg-green-100 text-green-700',
                                    'booking_cancelled' => 'bg-red-100 text-red-700',
                                    'booking_updated' => 'bg-yellow-100 text-yellow-700',
                                    default => 'bg-blue-100 text-blue-700',
                                };

                                $badgeText = match ($type) {
                                    'booking_created' => 'New Booking',
                                    'booking_cancelled' => 'Cancelled',
                                    'booking_updated' => 'Modified',
                                    default => 'Update',
                                };
                            @endphp

                            <a href="{{ route('vendor.notifications.read', $notification->id) }}"
                               class="block px-5 py-4 border-b border-gray-100 hover:bg-gray-50 transition
                               {{ is_null($notification->read_at) ? 'bg-blue-50/60' : 'bg-white' }}">

                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="px-2 py-0.5 rounded-full text-[11px] font-bold {{ $badgeClass }}">
                                                {{ $badgeText }}
                                            </span>

                                            @if(is_null($notification->read_at))
                                                <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                                            @endif
                                        </div>

                                        <p class="text-sm font-semibold text-gray-900">
                                            {{ $message }}
                                        </p>

                                        <p class="text-xs text-gray-600 mt-1">
                                            Vehicle: <span class="font-medium">{{ $vehicleName }}</span>
                                        </p>

                                        <p class="text-xs text-gray-600">
                                            Customer: <span class="font-medium">{{ $customerName }}</span>
                                        </p>
                                    </div>

                                    <span class="text-[11px] text-gray-400 whitespace-nowrap">
                                        {{ $notification->created_at?->diffForHumans() }}
                                    </span>
                                </div>
                            </a>
                        @empty
                            <div class="px-5 py-8 text-center">
                                <div class="mx-auto mb-3 flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="w-6 h-6"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor">
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 01-6 0m6 0H9" />
                                    </svg>
                                </div>

                                <p class="text-sm font-semibold text-gray-700">No notifications yet</p>
                                <p class="text-xs text-gray-500 mt-1">New bookings and cancellations will appear here.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="px-5 py-3 bg-gray-50 text-center">
                        <a href="{{ route('vendor.bookings.index') }}"
                           class="text-sm font-semibold text-blue-600 hover:text-blue-800">
                            View all bookings
                        </a>
                    </div>
                </div>
            </div>

            <span class="hidden sm:inline-flex px-4 py-2 rounded-full bg-gray-100 text-sm font-medium">
                Vendor
            </span>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="flex items-center gap-2 text-sm font-medium text-gray-700 hover:text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H9m4 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1" />
                    </svg>

                    <span class="hidden sm:inline">Logout</span>
                </button>
            </form>
        </div>
    </header>

    <!-- Mobile Overlay -->
    <div id="mobileSidebarOverlay" class="hidden fixed inset-0 bg-black/40 z-40 lg:hidden"></div>

    <!-- Mobile Sidebar -->
    <aside id="mobileSidebar"
           class="fixed top-0 left-0 h-full w-72 bg-white z-50 transform -translate-x-full transition-transform duration-300 lg:hidden border-r border-gray-200 px-5 py-6">

        <div class="flex items-center justify-between mb-6">
            <img src="{{ asset('images/logo/logo.png') }}" alt="VehicleRent" class="h-10 w-auto">

            <button type="button" id="mobileMenuClose"
                    class="flex items-center justify-center w-9 h-9 rounded-xl bg-gray-100 text-gray-700 hover:bg-red-50 hover:text-red-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <nav class="space-y-2">
            <a href="{{ route('vendor.dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('vendor.dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}">
                <span>Dashboard</span>
            </a>

            <a href="{{ route('vendor.vehicles.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('vendor.vehicles.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}">
                <span>Vehicles</span>
            </a>

            <a href="{{ route('vendor.bookings.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('vendor.bookings.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}">
                <span>Bookings</span>
            </a>

            <a href="{{ route('vendor.earnings') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('vendor.earnings') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}">
                <span>Earnings</span>
            </a>

            <a href="{{ route('vendor.reviews.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('vendor.reviews.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}">
                <span>Reviews</span>
            </a>

            <a href="{{ route('vendor.contact') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('vendor.contact') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}">
                <span>Contact</span>
            </a>
        </nav>
    </aside>

    <div class="flex">

        <!-- Desktop Sidebar -->
        <aside class="hidden lg:block w-64 min-h-[calc(100vh-64px)] bg-white border-r border-gray-200 px-5 py-6">
            <nav class="space-y-2">

                <!-- Dashboard -->
                <a href="{{ route('vendor.dashboard') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium
                   {{ request()->routeIs('vendor.dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}">

                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5h6v6H4V5zm10 0h6v6h-6V5zM4 15h6v4H4v-4zm10 0h6v4h-6v-4z" />
                    </svg>

                    Dashboard
                </a>

                <!-- Vehicles -->
                <a href="{{ route('vendor.vehicles.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium
                   {{ request()->routeIs('vendor.vehicles.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}">

                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V7a2 2 0 00-2-2h-3l-2-2H7a2 2 0 00-2 2v8m15 0l-2 6H6l-2-6h16z" />
                    </svg>

                    Vehicles
                </a>

                <!-- Bookings -->
                <a href="{{ route('vendor.bookings.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium
                   {{ request()->routeIs('vendor.bookings.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}">

                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M5 11h14M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                    </svg>

                    Bookings
                </a>

                <!-- Earnings -->
                <a href="{{ route('vendor.earnings') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium
                   {{ request()->routeIs('vendor.earnings') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}">

                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-2.21 0-4 1.12-4 2.5S9.79 13 12 13s4 1.12 4 2.5S14.21 18 12 18m0-10V6m0 12v-2m8-5a8 8 0 11-16 0 8 8 0 0116 0z" />
                    </svg>

                    Earnings
                </a>

                <!-- Reviews -->
                <a href="{{ route('vendor.reviews.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium
                   {{ request()->routeIs('vendor.reviews.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}">

                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.956a1 1 0 00.95.69h4.159c.969 0 1.371 1.24.588 1.81l-3.365 2.445a1 1 0 00-.364 1.118l1.286 3.956c.3.921-.755 1.688-1.539 1.118l-3.365-2.445a1 1 0 00-1.176 0L8.046 18.02c-.784.57-1.838-.197-1.539-1.118l1.286-3.956a1 1 0 00-.364-1.118L4.064 9.383c-.783-.57-.38-1.81.588-1.81h4.159a1 1 0 00.95-.69l1.288-3.956z" />
                    </svg>

                    Reviews
                </a>

                <!-- Contact -->
                <a href="{{ route('vendor.contact') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium
                   {{ request()->routeIs('vendor.contact') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}">

                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>

                    Contact
                </a>

            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-4 sm:p-6 overflow-x-hidden">
            @yield('content')
        </main>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const button = document.getElementById('notificationButton');
        const dropdown = document.getElementById('notificationDropdown');

        if (button && dropdown) {

            button.addEventListener('click', function (event) {
                event.stopPropagation();

                dropdown.classList.toggle('hidden');
            });

            document.addEventListener('click', function (event) {

                if (
                    !dropdown.contains(event.target) &&
                    !button.contains(event.target)
                ) {
                    dropdown.classList.add('hidden');
                }
            });
        }

        const mobileMenuButton = document.getElementById('mobileMenuButton');
        const mobileMenuClose = document.getElementById('mobileMenuClose');
        const mobileSidebar = document.getElementById('mobileSidebar');
        const mobileSidebarOverlay = document.getElementById('mobileSidebarOverlay');

        function openMobileSidebar() {
            mobileSidebar.classList.remove('-translate-x-full');
            mobileSidebarOverlay.classList.remove('hidden');
        }

        function closeMobileSidebar() {
            mobileSidebar.classList.add('-translate-x-full');
            mobileSidebarOverlay.classList.add('hidden');
        }

        if (mobileMenuButton && mobileSidebar && mobileSidebarOverlay) {
            mobileMenuButton.addEventListener('click', function (event) {
                event.stopPropagation();
                openMobileSidebar();
            });
        }

        if (mobileMenuClose) {
            mobileMenuClose.addEventListener('click', closeMobileSidebar);
        }

        if (mobileSidebarOverlay) {
            mobileSidebarOverlay.addEventListener('click', closeMobileSidebar);
        }
    });
</script>
</body>
</html>