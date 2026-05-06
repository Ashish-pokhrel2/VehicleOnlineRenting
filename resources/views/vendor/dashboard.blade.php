@extends('layouts.vendor')

@section('content')

<div class="max-w-[920px] mx-auto">

    <div class="mb-4">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-xs text-gray-500 mt-1">Welcome back! Here’s your business overview.</p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-4 gap-4 mb-5">
        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
            <div class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.5l-9-5-9 5 9 5 9-5z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.5l9 5 9-5" />
                </svg>
            </div>
            <p class="text-[11px] text-gray-500">Total Vehicles</p>
            <h2 class="text-lg font-bold text-gray-900">{{ $totalVehicles }}</h2>
            <p class="text-[10px] text-gray-400 mt-1">Vehicles listed by you</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
            <div class="w-9 h-9 bg-green-50 rounded-xl flex items-center justify-center mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M5 11h14M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                </svg>
            </div>
            <p class="text-[11px] text-gray-500">Total Bookings</p>
            <h2 class="text-lg font-bold text-gray-900">{{ $totalBookings }}</h2>
            <p class="text-[10px] text-gray-400 mt-1">All booking requests</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
            <div class="w-9 h-9 bg-purple-50 rounded-xl flex items-center justify-center mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-8 4h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
            <p class="text-[11px] text-gray-500">Total Revenue</p>
            <h2 class="text-lg font-bold text-gray-900">Rs. {{ number_format($totalRevenue) }}</h2>
            <p class="text-[10px] text-gray-400 mt-1">Confirmed bookings only</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
            <div class="w-9 h-9 bg-orange-50 rounded-xl flex items-center justify-center mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
            </div>
            <p class="text-[11px] text-gray-500">Pending Requests</p>
            <h2 class="text-lg font-bold text-gray-900">{{ $pendingRequests }}</h2>
            <p class="text-[10px] text-gray-400 mt-1">Needs attention</p>
        </div>
    </div>

    <!-- Recent + Top Vehicles -->
    <div class="grid grid-cols-2 gap-4 mb-5">
        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm min-h-[190px]">
            <h2 class="text-xs font-bold text-gray-900 mb-4">Recent Bookings</h2>

            @forelse($recentBookings->take(1) as $booking)
                @php $status = $booking->status->value ?? $booking->status; @endphp

                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset($booking->vehicle->image ?? 'images/vehicles/car1.jpg') }}" class="w-12 h-9 rounded-lg object-cover" alt="Vehicle">
                        <div>
                            <p class="text-[11px] font-semibold text-gray-900">{{ $booking->vehicle->name ?? 'Vehicle not found' }}</p>
                            <p class="text-[10px] text-gray-500">{{ $booking->customer->name ?? $booking->full_name }}</p>
                        </div>
                    </div>

                    <div class="text-right">
                        <p class="text-[11px] font-bold text-gray-900">Rs. {{ number_format($booking->total_price) }}</p>
                        <span class="inline-block mt-1 px-2 py-0.5 rounded-full text-[10px] bg-green-100 text-green-700">
                            {{ ucfirst($status) }}
                        </span>
                    </div>
                </div>
            @empty
                <p class="text-xs text-gray-400">No bookings yet</p>
            @endforelse
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm min-h-[190px]">
            <h2 class="text-xs font-bold text-gray-900 mb-4">Top Performing Vehicles</h2>

            <div class="space-y-4">
                @forelse($topVehicles->take(3) as $vehicle)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <img src="{{ asset($vehicle->image ?? 'images/vehicles/car1.jpg') }}" class="w-12 h-9 rounded-lg object-cover" alt="Vehicle">
                            <div>
                                <p class="text-[11px] font-semibold text-gray-900">{{ $vehicle->name }}</p>
                                <p class="text-[10px] text-gray-500">{{ $vehicle->category }}</p>
                            </div>
                        </div>

                        <div class="text-right">
                            <div class="flex items-center justify-end gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-3 h-3 text-yellow-400" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.966h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447 1.287 3.966c.3.921-.755 1.688-1.538 1.118L10 13.348l-3.368 2.886c-.783.57-1.838-.197-1.538-1.118l1.287-3.966-3.368-2.447c-.783-.57-.38-1.81.588-1.81h4.162l1.286-3.966z"/>
                                </svg>
                                <span class="text-[10px] font-semibold">{{ $vehicle->rating }}</span>
                            </div>
                            <p class="text-[10px] text-gray-500">Rs. {{ number_format($vehicle->price_per_day) }}/day</p>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-gray-400">No vehicles found</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Customer Reviews -->
    <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xs font-bold text-gray-900">Customer Reviews</h2>
            <a href="#" class="text-[11px] text-blue-600 font-medium">View All →</a>
        </div>

        <div class="grid grid-cols-3 gap-4 mb-4">
            <div class="bg-blue-50 rounded-xl p-4 text-center">
                <div class="flex justify-center items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.966h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447 1.287 3.966c.3.921-.755 1.688-1.538 1.118L10 13.348l-3.368 2.886c-.783.57-1.838-.197-1.538-1.118l1.287-3.966-3.368-2.447c-.783-.57-.38-1.81.588-1.81h4.162l1.286-3.966z"/>
                    </svg>
                    <p class="text-xl font-bold text-gray-900">5.0</p>
                </div>
                <p class="text-[10px] text-gray-500 mt-1">Average Rating</p>
            </div>

            <div class="bg-green-50 rounded-xl p-4 text-center">
                <p class="text-xl font-bold text-gray-900">5</p>
                <p class="text-[10px] text-gray-500 mt-1">Total Reviews</p>
            </div>

            <div class="bg-purple-50 rounded-xl p-4 text-center">
                <p class="text-xl font-bold text-gray-900">3</p>
                <p class="text-[10px] text-gray-500 mt-1">5-Star Reviews</p>
            </div>
        </div>

        @php
            $reviews = [
                ['initials' => 'JD', 'name' => 'John Doe', 'vehicle' => 'Mercedes S-Class', 'text' => 'Excellent service and vehicle. Highly recommend!'],
                ['initials' => 'AJ', 'name' => 'Alex Johnson', 'vehicle' => 'Porsche 911', 'text' => 'Amazing experience! The booking process was smooth and professional.'],
                ['initials' => 'ED', 'name' => 'Emily Davis', 'vehicle' => 'BMW Convertible', 'text' => 'Loved the vehicle. Perfect for our coastal drive.'],
            ];
        @endphp

        <div class="space-y-0">
            @foreach($reviews as $review)
                <div class="flex justify-between items-start gap-4 py-3 border-b border-gray-100 last:border-0">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-blue-600 text-white text-xs font-bold flex items-center justify-center">
                            {{ $review['initials'] }}
                        </div>
                        <div>
                            <p class="text-[11px] font-semibold text-gray-900">{{ $review['name'] }}</p>
                            <p class="text-[10px] text-gray-500">{{ $review['vehicle'] }}</p>
                            <p class="text-[11px] text-gray-600 mt-1">{{ $review['text'] }}</p>
                        </div>
                    </div>

                    <div class="flex gap-0.5 text-yellow-400">
                        @for($i = 0; $i < 5; $i++)
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-3 h-3" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.966h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447 1.287 3.966c.3.921-.755 1.688-1.538 1.118L10 13.348l-3.368 2.886c-.783.57-1.838-.197-1.538-1.118l1.287-3.966-3.368-2.447c-.783-.57-.38-1.81.588-1.81h4.162l1.286-3.966z"/>
                            </svg>
                        @endfor
                    </div>
                </div>
            @endforeach
        </div>

    </div>

</div>

@endsection