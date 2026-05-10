<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vendor Panel - VehicleRent</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 text-gray-900">

<div class="min-h-screen">

    <!-- Top Navbar -->
    <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-8">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/logo/logo.png') }}" alt="VehicleRent" class="h-10 w-auto">
        </div>

        <div class="flex items-center gap-4">
            <span class="px-4 py-2 rounded-full bg-gray-100 text-sm font-medium">
                Vendor
            </span>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="flex items-center gap-2 text-sm font-medium text-gray-700 hover:text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H9m4 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1" />
                    </svg>

                    Logout
                </button>
            </form>
        </div>
    </header>

    <div class="flex">

        <!-- Sidebar -->
        <aside class="w-64 min-h-[calc(100vh-64px)] bg-white border-r border-gray-200 px-5 py-6">
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
        <main class="flex-1 p-6">
            @yield('content')
        </main>

    </div>
</div>

</body>
</html>