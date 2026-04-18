@extends('layouts.app')

@section('title', 'Book Vehicle')

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-8">
            <a href="{{ route('vehicles.show', $vehicle) }}" class="text-blue-600 font-semibold hover:underline">
                Back to Vehicle Details
            </a>
        </div>

        @if ($isLoading)
            <div class="mb-6 bg-white border border-gray-200 rounded-xl p-4 text-gray-500">
                Loading booking details...
            </div>
        @elseif ($errorMessage)
            <div class="mb-6 bg-white border border-red-200 rounded-xl p-4 text-red-600">
                {{ $errorMessage }}
            </div>
        @endif

        @if(session('success'))
            <div class="mb-6 bg-white border border-green-200 rounded-xl p-4 text-green-600">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 bg-white border border-red-200 rounded-xl p-4 text-red-600">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="bookingForm" method="POST" action="{{ route('bookings.page.store') }}">
            @csrf
            <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-2 space-y-8">

                    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
                        <h1 class="text-3xl font-bold text-gray-900">Book Vehicle</h1>
                        <p class="text-gray-500 mt-2">
                            Complete the booking form below to reserve your selected vehicle.
                        </p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-5">Selected Vehicle</h2>

                        <div class="flex flex-col sm:flex-row gap-5">
                            <img
                                src="{{ asset($vehicle->image) }}"
                                alt="{{ $vehicle->name }}"
                                class="w-full sm:w-64 h-44 object-cover rounded-xl border border-gray-200"
                            >

                            <div class="flex-1">
                                <h3 class="text-2xl font-bold text-gray-900">{{ $vehicle->name }}</h3>
                                <p class="text-gray-500 mt-1">{{ $vehicle->vendor?->name ?? 'Unknown Vendor' }}</p>

                                <div class="flex flex-wrap gap-3 mt-4">
                                    <span class="bg-blue-100 text-blue-700 text-sm font-medium px-4 py-2 rounded-full">
                                        {{ $vehicle->category }}
                                    </span>

                                    <span class="bg-gray-100 text-gray-700 text-sm font-medium px-4 py-2 rounded-full">
                                        {{ $vehicle->location }}
                                    </span>

                                    <span class="bg-green-100 text-green-700 text-sm font-medium px-4 py-2 rounded-full">
                                        ${{ $vehicle->price_per_day }}/day
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-5">Rental Dates</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pick-up Date</label>
                                <input
                                    type="date"
                                    name="start_date"
                                    value="{{ old('start_date') }}"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Drop-off Date</label>
                                <input
                                    type="date"
                                    name="end_date"
                                    value="{{ old('end_date') }}"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required
                                >
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-5">Pickup Information</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pickup Location</label>
                                <input
                                    type="text"
                                    value="{{ $vehicle->location }}"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    readonly
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pickup Time</label>
                                <select class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @forelse ($pickupTimeSlots as $slot)
                                        <option>{{ $slot->label }}</option>
                                    @empty
                                        <option disabled>No pickup times available</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-5">Customer Information</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                                <input
                                    type="text"
                                    placeholder="Enter your full name"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                <input
                                    type="text"
                                    placeholder="Enter your phone number"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                                <input
                                    type="email"
                                    placeholder="Enter your email address"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
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

                    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-5">Confirmation Details</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                                <p class="text-sm text-gray-500">Selected Vehicle</p>
                                <p class="mt-2 font-semibold text-gray-900">{{ $vehicle->name }}</p>
                            </div>

                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                                <p class="text-sm text-gray-500">Vendor</p>
                                <p class="mt-2 font-semibold text-gray-900">{{ $vehicle->vendor?->name ?? 'Unknown Vendor' }}</p>
                            </div>

                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                                <p class="text-sm text-gray-500">Pickup City</p>
                                <p class="mt-2 font-semibold text-gray-900">{{ $vehicle->location }}</p>
                            </div>

                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                                <p class="text-sm text-gray-500">Vehicle Type</p>
                                <p class="mt-2 font-semibold text-gray-900">{{ $vehicle->type?->value ?? $vehicle->type }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 sticky top-24">
                        <h2 class="text-2xl font-semibold text-gray-900">Booking Summary</h2>
                        <p class="text-gray-500 mt-2">Review your booking details before final confirmation.</p>

                        <div class="mt-6 space-y-4">
                            <div class="flex justify-between items-center border-b border-gray-200 pb-3">
                                <span class="text-gray-600">Vehicle</span>
                                <span class="font-semibold text-gray-900">{{ $vehicle->name }}</span>
                            </div>

                            <div class="flex justify-between items-center border-b border-gray-200 pb-3">
                                <span class="text-gray-600">Category</span>
                                <span class="font-semibold text-gray-900">{{ $vehicle->category }}</span>
                            </div>

                            <div class="flex justify-between items-center border-b border-gray-200 pb-3">
                                <span class="text-gray-600">Availability</span>
                                <span class="font-semibold text-gray-900">{{ $availabilityLabel }}</span>
                            </div>

                            <div class="flex justify-between items-center border-b border-gray-200 pb-3">
                                <span class="text-gray-600">Location</span>
                                <span class="font-semibold text-gray-900">{{ $vehicle->location }}</span>
                            </div>

                            <div class="flex justify-between items-center border-b border-gray-200 pb-3">
                                <span class="text-gray-600">Rate Per Day</span>
                                <span class="font-semibold text-gray-900">${{ $vehicle->price_per_day }}</span>
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
                                type="submit"
                                form="bookingForm"
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
                                href="{{ route('vehicles.show', $vehicle) }}"
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
        </form>
    </div>
</div>
@endsection