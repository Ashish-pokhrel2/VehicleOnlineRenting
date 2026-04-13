@extends('layouts.app')

@section('title', 'Book Vehicle')

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-8">
            <a href="{{ route('vehicles.show', $id) }}" class="text-blue-600 font-semibold hover:underline">
                Back to Vehicle Details
            </a>
        </div>

        @php
            $vehicles = [
                1 => [
                    'id' => 1,
                    'name' => 'Mercedes S-Class',
                    'category' => 'Car',
                    'vendor' => 'Premium Rentals',
                    'price_per_day' => 150,
                    'location' => 'Biratnagar',
                    'image' => asset('images/vehicles/car1.jpg'),
                ],
                2 => [
                    'id' => 2,
                    'name' => 'Porsche 911',
                    'category' => 'Sports Car',
                    'vendor' => 'Premium Rentals',
                    'price_per_day' => 300,
                    'location' => 'Biratnagar',
                    'image' => asset('images/vehicles/car2.jpg'),
                ],
                3 => [
                    'id' => 3,
                    'name' => 'Range Rover Sport',
                    'category' => 'SUV',
                    'vendor' => 'Elite Motors',
                    'price_per_day' => 180,
                    'location' => 'Biratnagar',
                    'image' => asset('images/vehicles/car3.jpg'),
                ],
                4 => [
                    'id' => 4,
                    'name' => 'Harley Davidson',
                    'category' => 'Bike',
                    'vendor' => 'Elite Motors',
                    'price_per_day' => 80,
                    'location' => 'Dharan',
                    'image' => asset('images/vehicles/bike1.jpg'),
                ],
                5 => [
                    'id' => 5,
                    'name' => 'Electric Scooter',
                    'category' => 'Scooter',
                    'vendor' => 'Urban Mobility',
                    'price_per_day' => 25,
                    'location' => 'Itahari',
                    'image' => asset('images/vehicles/scooter.jpg'),
                ],
                6 => [
                    'id' => 6,
                    'name' => 'Honda Accord',
                    'category' => 'Sedan',
                    'vendor' => 'Urban Mobility',
                    'price_per_day' => 60,
                    'location' => 'Biratnagar',
                    'image' => asset('images/vehicles/car4.jpg'),
                ],
                7 => [
                    'id' => 7,
                    'name' => 'BMW Convertible',
                    'category' => 'Convertible',
                    'vendor' => 'Premium Rentals',
                    'price_per_day' => 220,
                    'location' => 'Biratnagar',
                    'image' => asset('images/vehicles/bmw.jpg'),
                ],
                8 => [
                    'id' => 8,
                    'name' => 'Family Van',
                    'category' => 'Van',
                    'vendor' => 'Elite Motors',
                    'price_per_day' => 90,
                    'location' => 'Biratnagar',
                    'image' => asset('images/vehicles/van.jpg'),
                ],
            ];

            $vehicle = $vehicles[$id] ?? $vehicles[1];

            $estimatedDays = 3;
            $serviceFee = 10;
            $subtotal = $vehicle['price_per_day'] * $estimatedDays;
            $total = $subtotal + $serviceFee;
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2 space-y-8">

                <!-- Page Header -->
                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
                    <h1 class="text-3xl font-bold text-gray-900">Book Vehicle</h1>
                    <p class="text-gray-500 mt-2">
                        Complete the booking form below to reserve your selected vehicle.
                    </p>
                </div>

                <!-- Selected Vehicle -->
                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-5">Selected Vehicle</h2>

                    <div class="flex flex-col sm:flex-row gap-5">
                        <img
                            src="{{ $vehicle['image'] }}"
                            alt="{{ $vehicle['name'] }}"
                            class="w-full sm:w-64 h-44 object-cover rounded-xl border border-gray-200"
                        >

                        <div class="flex-1">
                            <h3 class="text-2xl font-bold text-gray-900">{{ $vehicle['name'] }}</h3>
                            <p class="text-gray-500 mt-1">{{ $vehicle['vendor'] }}</p>

                            <div class="flex flex-wrap gap-3 mt-4">
                                <span class="bg-blue-100 text-blue-700 text-sm font-medium px-4 py-2 rounded-full">
                                    {{ $vehicle['category'] }}
                                </span>

                                <span class="bg-gray-100 text-gray-700 text-sm font-medium px-4 py-2 rounded-full">
                                    {{ $vehicle['location'] }}
                                </span>

                                <span class="bg-green-100 text-green-700 text-sm font-medium px-4 py-2 rounded-full">
                                    ${{ $vehicle['price_per_day'] }}/day
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Form -->
                <form class="space-y-8">

                    <!-- Rental Dates -->
                    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-5">Rental Dates</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pick-up Date</label>
                                <input
                                    type="date"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Drop-off Date</label>
                                <input
                                    type="date"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Pickup Information -->
                    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-5">Pickup Information</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pickup Location</label>
                                <input
                                    type="text"
                                    value="{{ $vehicle['location'] }}"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pickup Time</label>
                                <select class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option>9:00 AM</option>
                                    <option>11:00 AM</option>
                                    <option>1:00 PM</option>
                                    <option>3:00 PM</option>
                                    <option>5:00 PM</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Information -->
                    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-5">Customer Information</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                                <input
                                    type="text"
                                    placeholder="Enter your full name"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                <input
                                    type="text"
                                    placeholder="Enter your phone number"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                                <input
                                    type="email"
                                    placeholder="Enter your email address"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Citizenship / ID Number</label>
                                <input
                                    type="text"
                                    placeholder="Enter your citizenship or ID number"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                            </div>
                        </div>

                        <div class="mt-5">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Special Request</label>
                            <textarea
                                rows="4"
                                placeholder="Write any additional request here"
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            ></textarea>
                        </div>
                    </div>

                    <!-- Confirmation Details -->
                    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-5">Confirmation Details</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                                <p class="text-sm text-gray-500">Selected Vehicle</p>
                                <p class="mt-2 font-semibold text-gray-900">{{ $vehicle['name'] }}</p>
                            </div>

                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                                <p class="text-sm text-gray-500">Vendor</p>
                                <p class="mt-2 font-semibold text-gray-900">{{ $vehicle['vendor'] }}</p>
                            </div>

                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                                <p class="text-sm text-gray-500">Pickup City</p>
                                <p class="mt-2 font-semibold text-gray-900">{{ $vehicle['location'] }}</p>
                            </div>

                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                                <p class="text-sm text-gray-500">Vehicle Type</p>
                                <p class="mt-2 font-semibold text-gray-900">{{ $vehicle['category'] }}</p>
                            </div>
                        </div>
                    </div>

                </form>
            </div>

            <!-- Booking Summary -->
            <div>
                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 sticky top-24">
                    <h2 class="text-2xl font-semibold text-gray-900">Booking Summary</h2>
                    <p class="text-gray-500 mt-2">Review your booking details before final confirmation.</p>

                    <div class="mt-6 space-y-4">
                        <div class="flex justify-between items-center border-b border-gray-200 pb-3">
                            <span class="text-gray-600">Vehicle</span>
                            <span class="font-semibold text-gray-900">{{ $vehicle['name'] }}</span>
                        </div>

                        <div class="flex justify-between items-center border-b border-gray-200 pb-3">
                            <span class="text-gray-600">Category</span>
                            <span class="font-semibold text-gray-900">{{ $vehicle['category'] }}</span>
                        </div>

                        <div class="flex justify-between items-center border-b border-gray-200 pb-3">
                            <span class="text-gray-600">Location</span>
                            <span class="font-semibold text-gray-900">{{ $vehicle['location'] }}</span>
                        </div>

                        <div class="flex justify-between items-center border-b border-gray-200 pb-3">
                            <span class="text-gray-600">Rate Per Day</span>
                            <span class="font-semibold text-gray-900">${{ $vehicle['price_per_day'] }}</span>
                        </div>

                        <div class="flex justify-between items-center border-b border-gray-200 pb-3">
                            <span class="text-gray-600">Estimated Days</span>
                            <span class="font-semibold text-gray-900">{{ $estimatedDays }} days</span>
                        </div>

                        <div class="flex justify-between items-center border-b border-gray-200 pb-3">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-semibold text-gray-900">${{ $subtotal }}</span>
                        </div>

                        <div class="flex justify-between items-center border-b border-gray-200 pb-3">
                            <span class="text-gray-600">Service Fee</span>
                            <span class="font-semibold text-gray-900">${{ $serviceFee }}</span>
                        </div>

                        <div class="flex justify-between items-center pt-2">
                            <span class="text-lg font-semibold text-gray-900">Total Price</span>
                            <span class="text-2xl font-bold text-blue-600">${{ $total }}</span>
                        </div>
                    </div>

                    <div class="mt-6 space-y-3">
                        <button
                            type="button"
                            class="block w-full text-center bg-black text-white py-4 rounded-xl font-semibold hover:bg-gray-800 transition"
                        >
                            Confirm Booking
                        </button>

                        <button
                            type="button"
                            class="block w-full text-center bg-white border border-gray-300 text-gray-700 py-4 rounded-xl font-semibold hover:bg-gray-50 transition"
                        >
                            Proceed to Payment
                        </button>

                        <a
                            href="{{ route('vehicles.show', $vehicle['id']) }}"
                            class="block w-full text-center bg-white border border-gray-300 text-gray-700 py-4 rounded-xl font-semibold hover:bg-gray-50 transition"
                        >
                            Back to Details
                        </a>
                    </div>

                    <p class="text-xs text-gray-400 mt-5 leading-6">
                        Booking confirmation, payment processing, and final availability checking will be handled by backend integration.
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection