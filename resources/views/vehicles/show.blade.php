@extends('layouts.app')

@section('title', 'Vehicle Details')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-6">
            <a href="{{ route('vehicles.index') }}" class="text-blue-600 font-semibold hover:underline">
                Back to Vehicles
            </a>
        </div>

        @if ($isLoading)
            <div class="mb-6 bg-white border border-gray-200 rounded-xl p-4 text-gray-500">
                Loading vehicle details...
            </div>
        @elseif ($errorMessage)
            <div class="mb-6 bg-white border border-red-200 rounded-xl p-4 text-red-600">
                {{ $errorMessage }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2 space-y-8">

                <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-200">
                    <div class="bg-gray-50">
                        <img src="{{ asset($vehicleImages[0] ?? $vehicle->image) }}"
                             alt="{{ $vehicle->name }}"
                             class="w-full h-[300px] sm:h-[420px] object-contain">
                    </div>

                    <div class="p-5">
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                            @foreach ($vehicleImages as $image)
                                <div class="rounded-xl overflow-hidden border border-gray-200 bg-gray-50">
                                    <img src="{{ asset($image) }}"
                                         alt="{{ $vehicle->name }}"
                                         class="w-full h-24 object-contain">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">{{ $vehicle->name }}</h1>
                            <p class="text-gray-500 mt-2">Provided by {{ $vehicle->vendor?->name ?? 'Unknown Vendor' }}</p>

                            <div class="flex flex-wrap gap-3 mt-4">
                                <span class="bg-blue-100 text-blue-700 text-sm font-medium px-4 py-2 rounded-full">
                                    {{ $vehicle->category }}
                                </span>
                                <span class="bg-green-100 text-green-700 text-sm font-medium px-4 py-2 rounded-full">
                                    {{ $availabilityLabel }}
                                </span>
                                <span class="bg-yellow-100 text-yellow-700 text-sm font-medium px-4 py-2 rounded-full">
                                    Rating: {{ number_format($vehicle->rating, 1) }}/5
                                </span>
                            </div>
                        </div>

                        <div class="bg-gray-50 border border-gray-200 rounded-2xl px-6 py-5 min-w-[220px]">
                            <p class="text-sm text-gray-500">Daily Pricing</p>
                            <p class="mt-2 text-4xl font-bold text-blue-600">
                                Rs. {{ number_format($vehicle->price_per_day, 0) }}
                                <span class="text-lg font-medium text-gray-500">/day</span>
                            </p>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-3">Description</h2>
                        <p class="text-gray-600 leading-7">
                            {{ $vehicle->description }}
                        </p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-5">Vehicle Specifications</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                            <p class="text-sm text-gray-500">Transmission</p>
                            <p class="mt-2 font-semibold text-gray-900">{{ $vehicle->transmission }}</p>
                        </div>

                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                            <p class="text-sm text-gray-500">Fuel Type</p>
                            <p class="mt-2 font-semibold text-gray-900">{{ $vehicle->fuel }}</p>
                        </div>

                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                            <p class="text-sm text-gray-500">Seats</p>
                            <p class="mt-2 font-semibold text-gray-900">{{ $vehicle->seats }}</p>
                        </div>

                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                            <p class="text-sm text-gray-500">Location</p>
                            <p class="mt-2 font-semibold text-gray-900">{{ $vehicle->location }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-5">Features</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @forelse ($vehicle->features ?? [] as $feature)
                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-gray-700">
                                {{ $feature }}
                            </div>
                        @empty
                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-gray-700">
                                No features listed.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-5">Vendor Information</h2>

                    <div class="space-y-3">
                        <p class="text-gray-900 font-semibold">{{ $vehicle->vendor?->name ?? 'Unknown Vendor' }}</p>
                        <p class="text-gray-600">Vendor Rating: {{ number_format($vehicle->rating, 1) }}/5</p>
                        <p class="text-gray-600">{{ $vehicle->reviews }} reviews</p>
                        <p class="text-gray-600">Location: {{ $vehicle->location }}</p>
                    </div>
                </div>

            </div>

            <div>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sticky top-2">
                    <h2 class="text-2xl font-semibold text-gray-900">Book This Vehicle</h2>
                    <p class="text-gray-500 mt-2">Reserve this vehicle and continue to the booking page.</p>

                    <div class="mt-6 space-y-4">
                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                            <p class="text-sm text-gray-500">Price Per Day</p>
                            <p class="mt-2 text-2xl font-bold text-blue-600">
                                Rs. {{ number_format($vehicle->price_per_day, 0) }}/day
                            </p>
                        </div>

                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                            <p class="text-sm text-gray-500">Availability</p>
                            <p class="mt-2 font-semibold text-gray-900">{{ $availabilityLabel }}</p>
                        </div>

                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                            <p class="text-sm text-gray-500">Vehicle Type</p>
                            <p class="mt-2 font-semibold text-gray-900">{{ $vehicle->type?->value ?? $vehicle->type }}</p>
                        </div>
                    </div>

<div class="mt-6 space-y-3">
    @if($vehicle->available)
        <a href="{{ route('bookings.create', $vehicle) }}"
           class="block w-full text-center bg-black text-white py-4 rounded-xl font-semibold hover:bg-gray-800 transition">
            Book Now
        </a>
    @else
        <button type="button"
                disabled
                class="block w-full cursor-not-allowed text-center bg-gray-300 text-gray-500 py-4 rounded-xl font-semibold">
            Currently Unavailable
        </button>
    @endif

    <a href="{{ route('vehicles.index') }}"
       class="block w-full text-center bg-white border border-gray-300 text-gray-700 py-4 rounded-xl font-semibold hover:bg-gray-50 transition">
        Back to Listing
    </a>
</div>

<p class="text-xs text-gray-400 mt-5 leading-6">
    Final booking validation and confirmation will be handled by backend integration.
</p>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection