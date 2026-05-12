@extends('layouts.admin')

@section('content')
    <section class="mx-auto w-full max-w-7xl space-y-6">
        <!-- Header with Back Button -->
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div class="space-y-2">
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.vendors') }}" class="rounded-lg p-2 hover:bg-slate-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-4xl font-bold text-slate-900">{{ $vendor->name }}</h1>
                        <p class="mt-1 text-base text-slate-600">Vendor Profile & Analytics</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2">
                @php
                    $statusPillClass = $vendor->status === 'active'
                        ? 'bg-green-100 text-green-700'
                        : 'bg-slate-200 text-slate-700';
                @endphp
                <span class="rounded-lg px-4 py-2 text-sm font-medium {{ $statusPillClass }}">
                    {{ ucfirst($vendor->status) }}
                </span>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="rounded-2xl border border-black/10 bg-white p-6">
            <h2 class="mb-4 text-xl font-semibold text-slate-900">Contact Information</h2>
            <div class="grid gap-6 sm:grid-cols-3">
                <div>
                    <p class="text-sm text-slate-600">Email</p>
                    <p class="mt-1 text-base font-medium text-slate-900">{{ $vendor->email }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-600">Phone</p>
                    <p class="mt-1 text-base font-medium text-slate-900">{{ $vendor->phone }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-600">Joined Date</p>
                    <p class="mt-1 text-base font-medium text-slate-900">
                        {{ $vendor->joined_date?->format('M d, Y') ?? 'N/A' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Key Statistics -->
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-2xl border border-black/10 bg-white p-6">
                <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="5" width="18" height="16" rx="2" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 3v4M8 3v4M3 11h18" />
                    </svg>
                </div>
                <p class="text-sm text-slate-600">Total Bookings</p>
                <p class="mt-1 text-4xl font-bold text-slate-900">{{ $stats['totalBookings'] }}</p>
                <div class="mt-3 space-y-1 text-xs text-slate-500">
                    <p>✓ Confirmed: {{ $stats['confirmedBookings'] }}</p>
                    <p>◆ Completed: {{ $stats['completedBookings'] }}</p>
                    <p>✕ Cancelled: {{ $stats['cancelledBookings'] }}</p>
                </div>
            </div>

            <div class="rounded-2xl border border-black/10 bg-white p-6">
                <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-orange-100 text-orange-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7H14.5a3.5 3.5 0 0 1 0 7H6" />
                    </svg>
                </div>
                <p class="text-sm text-slate-600">Total Revenue</p>
                <p class="mt-1 text-4xl font-bold text-slate-900">RS {{ number_format($stats['totalRevenue'], 2) }}</p>
                <p class="mt-2 text-sm text-slate-500">Avg: RS {{ number_format($stats['avgRevenuePerBooking'], 2) }}/booking</p>
            </div>

            <div class="rounded-2xl border border-black/10 bg-white p-6">
                <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-purple-100 text-purple-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5 12 3l9 4.5M4.5 8.25V16.5L12 21l7.5-4.5V8.25M4.5 8.25 12 12.75l7.5-4.5" />
                    </svg>
                </div>
                <p class="text-sm text-slate-600">Vehicles</p>
                <p class="mt-1 text-4xl font-bold text-slate-900">{{ $stats['totalVehicles'] }}</p>
                <p class="mt-2 text-sm text-slate-500">{{ $stats['activeVehicles'] }} active</p>
            </div>

            <div class="rounded-2xl border border-black/10 bg-white p-6">
                <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-yellow-100 text-yellow-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                </div>
                <p class="text-sm text-slate-600">Average Rating</p>
                <p class="mt-1 text-4xl font-bold text-slate-900">
                    @if ($reviewStats['avgRating'] > 0)
                        ⭐ {{ $reviewStats['avgRating'] }}
                    @else
                        No rating
                    @endif
                </p>
                <p class="mt-2 text-sm text-slate-500">{{ $reviewStats['totalReviews'] }} reviews</p>
            </div>
        </div>

        <!-- Performance Metrics -->
        <div class="grid gap-6 lg:grid-cols-3">
            <!-- Acceptance Rate -->
            <div class="rounded-2xl border border-black/10 bg-white p-6">
                <h3 class="mb-4 text-lg font-semibold text-slate-900">Acceptance Rate</h3>
                <div class="flex items-end justify-between">
                    <div>
                        <p class="text-5xl font-bold text-green-600">{{ $stats['acceptRate'] }}%</p>
                        <p class="mt-2 text-sm text-slate-500">{{ $stats['confirmedBookings'] }} of {{ $stats['totalBookings'] }} bookings</p>
                    </div>
                    <div class="h-24 w-24 rounded-full bg-gradient-to-br from-green-100 to-green-50 flex items-center justify-center">
                        <div class="text-center">
                            <p class="text-xs text-green-700 font-medium">Confirmed</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cancellation Rate -->
            <div class="rounded-2xl border border-black/10 bg-white p-6">
                <h3 class="mb-4 text-lg font-semibold text-slate-900">Cancellation Rate</h3>
                <div class="flex items-end justify-between">
                    <div>
                        <p class="text-5xl font-bold text-red-600">{{ $stats['cancelRate'] }}%</p>
                        <p class="mt-2 text-sm text-slate-500">{{ $stats['cancelledBookings'] }} of {{ $stats['totalBookings'] }} bookings</p>
                    </div>
                    <div class="h-24 w-24 rounded-full bg-gradient-to-br from-red-100 to-red-50 flex items-center justify-center">
                        <div class="text-center">
                            <p class="text-xs text-red-700 font-medium">Cancelled</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Completion Rate -->
            <div class="rounded-2xl border border-black/10 bg-white p-6">
                <h3 class="mb-4 text-lg font-semibold text-slate-900">Completion Rate</h3>
                <div class="flex items-end justify-between">
                    <div>
                        <p class="text-5xl font-bold text-blue-600">{{ $stats['completionRate'] }}%</p>
                        <p class="mt-2 text-sm text-slate-500">{{ $stats['completedBookings'] }} of {{ $stats['totalBookings'] }} bookings</p>
                    </div>
                    <div class="h-24 w-24 rounded-full bg-gradient-to-br from-blue-100 to-blue-50 flex items-center justify-center">
                        <div class="text-center">
                            <p class="text-xs text-blue-700 font-medium">Completed</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rating Distribution -->
        @if ($reviewStats['totalReviews'] > 0)
            <div class="rounded-2xl border border-black/10 bg-white p-6">
                <h2 class="mb-6 text-xl font-semibold text-slate-900">Review Distribution</h2>
                <div class="space-y-4">
                    @foreach ([5, 4, 3, 2, 1] as $rating)
                        @php
                            $count = $reviewStats['ratingDistribution'][$rating];
                            $percentage = $reviewStats['totalReviews'] > 0 ? round(($count / $reviewStats['totalReviews']) * 100, 1) : 0;
                        @endphp
                        <div class="flex items-center gap-4">
                            <div class="w-12 text-right">
                                <span class="text-sm font-medium text-slate-900">{{ $rating }} ⭐</span>
                            </div>
                            <div class="flex-1">
                                <div class="h-2 rounded-full bg-slate-200">
                                    <div class="h-full rounded-full bg-yellow-400 transition-all" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                            <div class="w-16 text-right text-sm text-slate-500">
                                {{ $count }} ({{ $percentage }}%)
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Vehicle Performance -->
        @if ($vehicleMetrics->count() > 0)
            <div class="rounded-2xl border border-black/10 bg-white p-6">
                <h2 class="mb-6 text-xl font-semibold text-slate-900">Vehicle Performance</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead class="border-b border-black/10 bg-slate-50 text-slate-700">
                            <tr>
                                <th class="px-6 py-4 font-semibold">Vehicle Name</th>
                                <th class="px-6 py-4 font-semibold">Price/Day</th>
                                <th class="px-6 py-4 font-semibold">Bookings</th>
                                <th class="px-6 py-4 font-semibold">Revenue</th>
                                <th class="px-6 py-4 font-semibold">Status</th>
                                <th class="px-6 py-4 font-semibold">Rating</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-700">
                            @foreach ($vehicleMetrics as $vehicle)
                                <tr class="border-b border-black/10 last:border-none">
                                    <td class="px-6 py-4 font-medium">{{ $vehicle['name'] }}</td>
                                    <td class="px-6 py-4">RS {{ number_format($vehicle['pricePerDay'], 2) }}</td>
                                    <td class="px-6 py-4">{{ $vehicle['bookingCount'] }}</td>
                                    <td class="px-6 py-4 font-medium">RS {{ number_format($vehicle['revenue'], 2) }}</td>
                                    <td class="px-6 py-4">
                                        <span class="rounded px-2 py-1 text-xs font-medium {{ $vehicle['available'] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $vehicle['available'] ? 'Available' : 'Unavailable' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($vehicle['rating'] > 0)
                                            ⭐ {{ $vehicle['rating'] }} ({{ $vehicle['reviews'] }})
                                        @else
                                            No rating
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Recent Bookings -->
        @if ($recentBookings->count() > 0)
            <div class="rounded-2xl border border-black/10 bg-white p-6">
                <h2 class="mb-6 text-xl font-semibold text-slate-900">Recent Bookings</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead class="border-b border-black/10 bg-slate-50 text-slate-700">
                            <tr>
                                <th class="px-6 py-4 font-semibold">Booking ID</th>
                                <th class="px-6 py-4 font-semibold">Customer</th>
                                <th class="px-6 py-4 font-semibold">Vehicle</th>
                                <th class="px-6 py-4 font-semibold">Dates</th>
                                <th class="px-6 py-4 font-semibold">Amount</th>
                                <th class="px-6 py-4 font-semibold">Status</th>
                                <th class="px-6 py-4 font-semibold">Date</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-700">
                            @foreach ($recentBookings as $booking)
                                @php
                                    $statusClasses = [
                                        'Pending' => 'bg-amber-100 text-amber-700',
                                        'Confirmed' => 'bg-blue-100 text-blue-700',
                                        'Completed' => 'bg-green-100 text-green-700',
                                        'Cancelled' => 'bg-red-100 text-red-700',
                                    ];
                                    $statusClass = $statusClasses[$booking->status->value] ?? 'bg-slate-100 text-slate-700';
                                @endphp
                                <tr class="border-b border-black/10 last:border-none">
                                    <td class="px-6 py-4 font-medium">#{{ $booking->id }}</td>
                                    <td class="px-6 py-4">{{ $booking->customer->name }}</td>
                                    <td class="px-6 py-4">{{ $booking->vehicle->name }}</td>
                                    <td class="px-6 py-4 text-xs">
                                        {{ $booking->start_date->format('M d') }} - {{ $booking->end_date->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 font-medium">RS {{ number_format($booking->total_price, 2) }}</td>
                                    <td class="px-6 py-4">
                                        <span class="rounded px-2 py-1 text-xs font-medium {{ $statusClass }}">
                                            {{ $booking->status->value }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-slate-500">{{ $booking->created_at->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Recent Reviews -->
        @if ($recentReviews->count() > 0)
            <div class="rounded-2xl border border-black/10 bg-white p-6">
                <h2 class="mb-6 text-xl font-semibold text-slate-900">Recent Reviews</h2>
                <div class="space-y-4">
                    @foreach ($recentReviews as $review)
                        <div class="space-y-2 border-b border-black/10 pb-4 last:border-none">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-sm font-medium text-slate-900">{{ $review->customer->name }}</p>
                                    <p class="text-xs text-slate-500">{{ $review->vehicle->name }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-yellow-500">⭐ {{ $review->rating }}</p>
                                    <p class="text-xs text-slate-500">{{ $review->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                            @if ($review->comment)
                                <p class="text-sm text-slate-600">{{ $review->comment }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </section>
@endsection
