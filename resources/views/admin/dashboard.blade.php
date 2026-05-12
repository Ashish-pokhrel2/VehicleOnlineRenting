@extends('layouts.admin')

@section('content')
    @php
        $statusClasses = [
            'Confirmed' => 'bg-green-100 text-green-700',
            'Pending' => 'bg-amber-100 text-amber-700',
            'Completed' => 'bg-blue-100 text-blue-700',
            'Cancelled' => 'bg-red-100 text-red-700',
        ];
    @endphp

    <section class="mx-auto w-full max-w-6xl space-y-8">
        <div class="space-y-2">
            <h1 class="text-4xl font-bold text-slate-900">Admin Dashboard</h1>
            <p class="text-base text-slate-600">System overview and analytics</p>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
            <a href="{{ route('admin.users') }}" class="block rounded-lg border border-black/10 bg-white p-6 transition hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M22 21v-2a4 4 0 0 0-3-3.87" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                </div>
                <p class="text-sm text-slate-600">Total Users</p>
                <p class="mt-1 text-4xl font-bold text-slate-900">{{ $stats['totalUsers'] }}</p>
                <p class="mt-2 text-sm text-slate-500">{{ $stats['activeUsers'] }} active</p>
            </a>

            <a href="{{ route('vehicles.index') }}" class="block rounded-lg border border-black/10 bg-white p-6 transition hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-green-100 text-green-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5 12 3l9 4.5M4.5 8.25V16.5L12 21l7.5-4.5V8.25M4.5 8.25 12 12.75l7.5-4.5" />
                    </svg>
                </div>
                <p class="text-sm text-slate-600">Total Vehicles</p>
                <p class="mt-1 text-4xl font-bold text-slate-900">{{ $stats['totalVehicles'] }}</p>
                <p class="mt-2 text-sm text-slate-500">{{ $stats['availableVehicles'] }} available</p>
            </a>

            <a href="{{ route('admin.bookings') }}" class="block rounded-lg border border-black/10 bg-white p-6 transition hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-violet-100 text-violet-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="5" width="18" height="16" rx="2" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 3v4M8 3v4M3 11h18" />
                    </svg>
                </div>
                <p class="text-sm text-slate-600">Total Bookings</p>
                <p class="mt-1 text-4xl font-bold text-slate-900">{{ $stats['totalBookings'] }}</p>
                <p class="mt-2 text-sm text-slate-500">{{ $stats['pendingBookings'] }} pending</p>
            </a>

            <a href="{{ route('admin.bookings') }}" class="block rounded-lg border border-black/10 bg-white p-6 transition hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-orange-100 text-orange-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7H14.5a3.5 3.5 0 0 1 0 7H6" />
                    </svg>
                </div>
                <p class="text-sm text-slate-600">Total Revenue</p>
                <p class="mt-1 text-4xl font-bold text-slate-900">RS {{ number_format($stats['totalRevenue'], 0) }}</p>
                <p class="mt-2 text-sm text-slate-500">From all bookings</p>
            </a>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-lg border border-black/10 bg-white p-6">
                <h2 class="mb-4 text-2xl font-semibold text-slate-900">Recent Activity</h2>

                <div class="space-y-4">
                    @forelse ($recentActivities as $activity)
                        <a href="{{ $activity['url'] }}" class="flex items-start gap-3 border-b border-black/10 pb-4 transition hover:text-blue-600 last:border-none last:pb-0">
                            <div class="mt-0.5 inline-flex h-8 w-8 items-center justify-center rounded-lg bg-blue-100 text-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 13h5l2-6 3 12 2-6h4" />
                                </svg>
                            </div>
                            <div class="space-y-1">
                                <p class="text-xl font-medium text-slate-800">{{ $activity['title'] }}</p>
                                <p class="text-sm text-slate-600">{{ $activity['meta'] }}</p>
                                <p class="text-base text-slate-500">{{ $activity['time'] }}</p>
                            </div>
                        </a>
                    @empty
                        <p class="text-base text-slate-500">No recent activity yet.</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-lg border border-black/10 bg-white p-6">
                <h2 class="mb-5 text-2xl font-semibold text-slate-900">User Distribution</h2>

                <div class="space-y-6">
                    @foreach ($userDistribution as $row)
                        <div class="space-y-2">
                            <div class="flex items-center justify-between text-base">
                                <span class="font-medium text-slate-700">{{ $row['label'] }}</span>
                                <span class="text-slate-600">{{ $row['count'] }} ({{ $row['percentage'] }}%)</span>
                            </div>
                            <div class="h-2 overflow-hidden rounded-full bg-slate-200">
                                <div class="h-full rounded-full bg-blue-600" style="width: {{ $row['percentage'] }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="rounded-lg border border-black/10 bg-white p-6">
            <h2 class="mb-4 text-2xl font-semibold text-slate-900">Latest Bookings</h2>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left">
                    <thead>
                        <tr class="border-b border-black/10 text-base text-slate-600">
                            <th class="px-3 py-3 font-medium">Vehicle</th>
                            <th class="px-3 py-3 font-medium">Customer</th>
                            <th class="px-3 py-3 font-medium">Date</th>
                            <th class="px-3 py-3 font-medium">Amount</th>
                            <th class="px-3 py-3 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-base text-slate-700">
                        @foreach ($latestBookings as $booking)
                            @php
                                $status = $booking->status->value;
                            @endphp
                            <tr class="border-b border-black/10 last:border-none">
                                <td class="px-3 py-4">
                                    <div class="flex items-center gap-3">
                                        <img
                                            src="{{ asset($booking->vehicle?->image ?? 'images/logo/logo.png') }}"
                                            alt="{{ $booking->vehicle?->name ?? 'Vehicle' }}"
                                            class="h-10 w-10 rounded object-cover"
                                        >
                                        <span class="font-medium text-slate-800">{{ $booking->vehicle?->name ?? 'Unknown Vehicle' }}</span>
                                    </div>
                                </td>
                                <td class="px-3 py-4">{{ $booking->customer?->name ?? 'Unknown Customer' }}</td>
                                <td class="px-3 py-4">{{ optional($booking->start_date)->format('n/j/Y') }}</td>
                                <td class="px-3 py-4 font-semibold text-slate-900">RS {{ number_format((float) $booking->total_price, 0) }}</td>
                                <td class="px-3 py-4">
                                    <span class="rounded-full px-2.5 py-1 text-sm {{ $statusClasses[$status] ?? 'bg-slate-100 text-slate-700' }}">
                                        {{ $status }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
