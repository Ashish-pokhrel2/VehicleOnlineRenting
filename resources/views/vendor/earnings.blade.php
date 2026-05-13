@extends('layouts.vendor')

@section('content')

<div class="max-w-[920px] mx-auto">

    <!-- Header -->
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Vendor Earnings</h1>
            <p class="text-xs text-gray-500 mt-1">Track your rental income and transaction history</p>
        </div>

        <div class="flex items-center gap-1 bg-white border border-gray-200 rounded-xl p-1 shadow-sm">
            <a href="{{ route('vendor.earnings', ['period' => 'week']) }}"
               class="px-4 py-2 rounded-lg text-xs font-semibold transition
               {{ $period === 'week' ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                Week
            </a>

            <a href="{{ route('vendor.earnings', ['period' => 'month']) }}"
               class="px-4 py-2 rounded-lg text-xs font-semibold transition
               {{ $period === 'month' ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                Month
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-4 gap-4 mb-4">

        <a href="{{ route('vendor.bookings.index') }}"
           class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm hover:shadow-md hover:border-blue-200 transition block">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-12 h-12 rounded-2xl bg-green-100 flex items-center justify-center text-green-600">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 8c-2.21 0-4 1.12-4 2.5S9.79 13 12 13s4 1.12 4 2.5S14.21 18 12 18m0-10V6m0 12v-2" />
    </svg>
</div>
                <p class="text-[11px] text-gray-500">Total Earnings</p>
            </div>
            <h2 class="text-xl font-bold text-gray-900">Rs. {{ number_format($totalEarnings) }}</h2>
            <p class="text-[10px] text-green-600 mt-2">Confirmed bookings only</p>
        </a>

        <a href="{{ route('vendor.bookings.index') }}"
           class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm hover:shadow-md hover:border-blue-200 transition block">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-8 h-8 rounded-lg bg-yellow-50 text-yellow-600 flex items-center justify-center text-sm font-bold">
                    ⏱
                </div>
                <p class="text-[11px] text-gray-500">Pending Payments</p>
            </div>
            <h2 class="text-xl font-bold text-gray-900">Rs. {{ number_format($pendingPayments) }}</h2>
            <p class="text-[10px] text-gray-400 mt-2">{{ $pendingBookings }} transaction{{ $pendingBookings === 1 ? '' : 's' }}</p>
        </a>

        <a href="{{ route('vendor.bookings.index') }}"
           class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm hover:shadow-md hover:border-blue-200 transition block">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-bold">
                    ✓
                </div>
                <p class="text-[11px] text-gray-500">Confirmed Rentals</p>
            </div>
            <h2 class="text-xl font-bold text-gray-900">{{ $confirmedBookings }}</h2>
            <p class="text-[10px] text-gray-400 mt-2">Successful bookings</p>
        </a>

        <a href="{{ route('vendor.bookings.index') }}"
           class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm hover:shadow-md hover:border-blue-200 transition block">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-sm font-bold">
                    📅
                </div>
                <p class="text-[11px] text-gray-500">Active Rentals</p>
            </div>
            <h2 class="text-xl font-bold text-gray-900">{{ $activeRentals }}</h2>
            <p class="text-[10px] text-gray-400 mt-2">Currently rented</p>
        </a>

    </div>

    <!-- Earnings Trend -->
    <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm mb-4">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-sm font-bold text-gray-900">Earnings Trend</h2>
                <p class="text-[11px] text-gray-500 mt-1">
                    {{ $period === 'week' ? 'Last 7 days confirmed revenue' : 'Last 6 months confirmed revenue' }}
                </p>
            </div>
<a href="{{ route('vendor.earnings', ['period' => $period === 'week' ? 'month' : 'week']) }}"
   class="text-[11px] font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded-full transition">

    {{ $period === 'week' ? 'Month View' : 'Week View' }}

</a>
        </div>

        <div class="h-44 flex items-end gap-4 border-b border-gray-100 pb-3">
            @foreach($chartValues as $index => $value)
                @php
                    $height = $value > 0 ? max(10, ($value / $maxChartValue) * 100) : 5;
                @endphp

                <div class="flex-1 flex flex-col items-center justify-end h-full">
                    <div class="w-full rounded-t-lg bg-blue-600 hover:bg-blue-700 transition"
                         style="height: {{ $height }}%;">
                    </div>
                </div>
            @endforeach
        </div>

        <div class="flex justify-between mt-3">
            @foreach($chartLabels as $label)
                <p class="text-[11px] text-gray-500">{{ $label }}</p>
            @endforeach
        </div>
    </div>

    <!-- Top Vehicle + Extra Stats -->
    <div class="grid grid-cols-4 gap-4 mb-4">
        <div class="col-span-2 bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-bold text-gray-900">Top Earning Vehicle</h2>
                <a href="{{ route('vendor.vehicles.index') }}" class="text-[11px] font-semibold text-blue-600 hover:text-blue-800">
                    View All →
                </a>
            </div>

            @if($topEarningVehicle && $topEarningVehicle->vehicle)
                <div class="flex items-center gap-4">
                    <img src="{{ asset($topEarningVehicle->vehicle->image ?? 'images/vehicles/car1.jpg') }}"
                         alt="Vehicle"
                         class="w-20 h-16 rounded-xl object-cover">

                    <div class="flex-1">
                        <h3 class="text-sm font-bold text-gray-900">
                            {{ $topEarningVehicle->vehicle->name }}
                        </h3>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $topEarningVehicle->vehicle->category ?? 'Vehicle' }}
                        </p>

                        <div class="grid grid-cols-2 gap-3 mt-3">
                            <div class="bg-green-50 rounded-lg p-2">
                                <p class="text-[10px] text-gray-500">Earned</p>
                                <p class="text-xs font-bold text-gray-900">
                                    Rs. {{ number_format($topEarningVehicle->total_earned) }}
                                </p>
                            </div>

                            <div class="bg-blue-50 rounded-lg p-2">
                                <p class="text-[10px] text-gray-500">Bookings</p>
                                <p class="text-xs font-bold text-gray-900">
                                    {{ $topEarningVehicle->total_bookings }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <p class="text-xs text-gray-400">No earnings yet</p>
            @endif
        </div>

        <a href="{{ route('vendor.bookings.index') }}"
           class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm hover:shadow-md hover:border-blue-200 transition block">
            <p class="text-xs text-gray-500">This Month</p>
            <h2 class="text-xl font-bold text-gray-900 mt-2">Rs. {{ number_format($thisMonthEarnings) }}</h2>
            <p class="text-[10px] text-gray-400 mt-2">Current month revenue</p>
        </a>

        <a href="{{ route('vendor.bookings.index') }}"
           class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm hover:shadow-md hover:border-blue-200 transition block">
            <p class="text-xs text-gray-500">Cancelled</p>
            <h2 class="text-xl font-bold text-gray-900 mt-2">{{ $cancelledBookings }}</h2>
            <p class="text-[10px] text-gray-400 mt-2">Cancelled bookings</p>
        </a>
    </div>

    <!-- Transactions -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-4 py-4 border-b border-gray-100">
            <div>
                <h2 class="text-sm font-bold text-gray-900">Recent Transactions</h2>
                <p class="text-[11px] text-gray-500 mt-1">Latest booking payments and statuses</p>
            </div>

            <a href="{{ route('vendor.bookings.index') }}"
               class="text-xs font-semibold text-blue-600 hover:text-blue-800">
                View All →
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-[11px] font-bold text-gray-500 uppercase">Vehicle</th>
                        <th class="px-4 py-3 text-[11px] font-bold text-gray-500 uppercase">Customer</th>
                        <th class="px-4 py-3 text-[11px] font-bold text-gray-500 uppercase">Date</th>
                        <th class="px-4 py-3 text-[11px] font-bold text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-[11px] font-bold text-gray-500 uppercase text-right">Amount</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse($transactions as $booking)
                        @php
                            $status = $booking->status->value ?? $booking->status;

                            $statusClass = match (strtolower($status)) {
                                'pending' => 'bg-yellow-100 text-yellow-700',
                                'confirmed' => 'bg-green-100 text-green-700',
                                'completed' => 'bg-blue-100 text-blue-700',
                                'cancelled' => 'bg-red-100 text-red-700',
                                'rejected' => 'bg-red-100 text-red-700',
                                default => 'bg-gray-100 text-gray-700',
                            };
                        @endphp

                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <img src="{{ asset($booking->vehicle->image ?? 'images/vehicles/car1.jpg') }}"
                                         class="w-11 h-8 rounded-lg object-cover"
                                         alt="Vehicle">

                                    <div>
                                        <p class="text-xs font-semibold text-gray-900">
                                            {{ $booking->vehicle->name ?? 'Vehicle not found' }}
                                        </p>
                                        <p class="text-[11px] text-gray-500">
                                            {{ $booking->vehicle->category ?? 'Vehicle' }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-3">
                                <p class="text-xs font-semibold text-gray-900">
                                    {{ $booking->customer->name ?? $booking->full_name }}
                                </p>
                                <p class="text-[11px] text-gray-500">
                                    {{ $booking->email }}
                                </p>
                            </td>

                            <td class="px-4 py-3">
                                <p class="text-xs text-gray-700">
                                    {{ $booking->start_date?->format('M d') }} - {{ $booking->end_date?->format('M d') }}
                                </p>
                                <p class="text-[11px] text-gray-500">
                                    {{ $booking->created_at?->format('M d, Y') }}
                                </p>
                            </td>

                            <td class="px-4 py-3">
                                <span class="inline-flex px-3 py-1 rounded-full text-[11px] font-bold {{ $statusClass }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>

                            <td class="px-4 py-3 text-right">
                                <p class="text-xs font-bold text-gray-900">
                                    Rs. {{ number_format($booking->total_price) }}
                                </p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center">
                                <p class="text-sm font-semibold text-gray-700">No transactions yet</p>
                                <p class="text-xs text-gray-500 mt-1">Bookings will appear here once customers start booking your vehicles.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection