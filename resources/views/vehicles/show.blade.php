@extends('layouts.app')

@section('title', 'Vehicle Details')

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-8">
            <a href="{{ route('vehicles.index') }}" class="text-blue-600 font-semibold hover:underline">
                Back to Vehicles
            </a>
        </div>

        @php
            $vehicles = [
                1 => [
                    'id' => 1,
                    'name' => 'Mercedes S-Class',
                    'category' => 'Car',
                    'vendor' => 'Premium Rentals',
                    'vendor_rating' => '4.9',
                    'reviews' => 156,
                    'price_per_day' => 150,
                    'location' => 'Biratnagar',
                    'transmission' => 'Automatic',
                    'fuel_type' => 'Hybrid',
                    'seats' => 5,
                    'availability' => 'Available Now',
                    'description' => 'The Mercedes S-Class offers premium comfort, elegant styling, and a smooth driving experience. It is perfect for executive travel, family trips, and special events.',
                    'features' => [
                        'Air Conditioning',
                        'Bluetooth Connectivity',
                        'Rear Camera',
                        'Leather Seats',
                        'GPS Navigation',
                        'ABS Safety System',
                        'USB Charging Port',
                        'Premium Sound System',
                    ],
                    'images' => [
                        asset('images/vehicles/car1.jpg'),
                        asset('images/vehicles/car2.jpg'),
                        asset('images/vehicles/car3.jpg'),
                        asset('images/vehicles/car4.jpg'),
                    ]
                ],

                2 => [
                    'id' => 2,
                    'name' => 'Porsche 911',
                    'category' => 'Sports Car',
                    'vendor' => 'Premium Rentals',
                    'vendor_rating' => '5.0',
                    'reviews' => 89,
                    'price_per_day' => 300,
                    'location' => 'Biratnagar',
                    'transmission' => 'Manual',
                    'fuel_type' => 'Gasoline',
                    'seats' => 2,
                    'availability' => 'Available Now',
                    'description' => 'The Porsche 911 is built for speed, style, and performance. It is a perfect choice for drivers who want a luxury sports car experience.',
                    'features' => [
                        'Sport Mode',
                        'Bluetooth Connectivity',
                        'Rear Camera',
                        'Leather Interior',
                        'Premium Sound System',
                        'ABS Safety System',
                        'Touchscreen Display',
                        'USB Charging Port',
                    ],
                    'images' => [
                        asset('images/vehicles/car2.jpg'),
                        asset('images/vehicles/car1.jpg'),
                        asset('images/vehicles/car3.jpg'),
                        asset('images/vehicles/bmw.jpg'),
                    ]
                ],

                3 => [
                    'id' => 3,
                    'name' => 'Range Rover Sport',
                    'category' => 'SUV',
                    'vendor' => 'Elite Motors',
                    'vendor_rating' => '4.8',
                    'reviews' => 124,
                    'price_per_day' => 180,
                    'location' => 'Biratnagar',
                    'transmission' => 'Automatic',
                    'fuel_type' => 'Diesel',
                    'seats' => 7,
                    'availability' => 'Available Now',
                    'description' => 'Range Rover Sport provides luxury, comfort, and off-road capability. It is suitable for long trips, family use, and premium travel.',
                    'features' => [
                        'Air Conditioning',
                        '4WD Capability',
                        'Rear Camera',
                        'Leather Seats',
                        'GPS Navigation',
                        'ABS Safety System',
                        'USB Charging Port',
                        'Large Cargo Space',
                    ],
                    'images' => [
                        asset('images/vehicles/car3.jpg'),
                        asset('images/vehicles/car1.jpg'),
                        asset('images/vehicles/car4.jpg'),
                        asset('images/vehicles/van.jpg'),
                    ]
                ],

                4 => [
                    'id' => 4,
                    'name' => 'Harley Davidson',
                    'category' => 'Bike',
                    'vendor' => 'Elite Motors',
                    'vendor_rating' => '4.7',
                    'reviews' => 67,
                    'price_per_day' => 80,
                    'location' => 'Dharan',
                    'transmission' => 'Manual',
                    'fuel_type' => 'Gasoline',
                    'seats' => 2,
                    'availability' => 'Available Now',
                    'description' => 'Harley Davidson is a powerful cruiser bike built for comfort and style. It is ideal for road trips, leisure rides, and premium biking experiences.',
                    'features' => [
                        'Electric Start',
                        'Dual Seating',
                        'Disc Brakes',
                        'Comfort Suspension',
                        'Digital Meter',
                        'LED Headlamp',
                        'Fuel Efficient Engine',
                        'Roadside Assistance',
                    ],
                    'images' => [
                        asset('images/vehicles/bike1.jpg'),
                        asset('images/vehicles/bike1.jpg'),
                        asset('images/vehicles/car2.jpg'),
                        asset('images/vehicles/scooter.jpg'),
                    ]
                ],

                5 => [
                    'id' => 5,
                    'name' => 'Electric Scooter',
                    'category' => 'Scooter',
                    'vendor' => 'Urban Mobility',
                    'vendor_rating' => '4.5',
                    'reviews' => 234,
                    'price_per_day' => 25,
                    'location' => 'Itahari',
                    'transmission' => 'Automatic',
                    'fuel_type' => 'Electric',
                    'seats' => 2,
                    'availability' => 'Available Now',
                    'description' => 'This electric scooter is lightweight, economical, and easy to ride. It is perfect for city travel, short-distance commuting, and eco-friendly mobility.',
                    'features' => [
                        'Electric Motor',
                        'Fast Charging',
                        'Digital Display',
                        'LED Lights',
                        'Disc Brakes',
                        'Comfort Seat',
                        'Low Maintenance',
                        'Silent Ride',
                    ],
                    'images' => [
                        asset('images/vehicles/scooter.jpg'),
                        asset('images/vehicles/scooter.jpg'),
                        asset('images/vehicles/bike1.jpg'),
                        asset('images/vehicles/car4.jpg'),
                    ]
                ],

                6 => [
                    'id' => 6,
                    'name' => 'Honda Accord',
                    'category' => 'Sedan',
                    'vendor' => 'Urban Mobility',
                    'vendor_rating' => '4.6',
                    'reviews' => 198,
                    'price_per_day' => 60,
                    'location' => 'Biratnagar',
                    'transmission' => 'Automatic',
                    'fuel_type' => 'Gasoline',
                    'seats' => 5,
                    'availability' => 'Available Now',
                    'description' => 'Honda Accord is a practical and comfortable sedan ideal for daily travel, business use, and family trips. It offers a reliable and smooth driving experience.',
                    'features' => [
                        'Air Conditioning',
                        'Bluetooth Connectivity',
                        'Rear Camera',
                        'Spacious Interior',
                        'ABS Safety System',
                        'USB Charging Port',
                        'Comfort Seating',
                        'Fuel Efficient Engine',
                    ],
                    'images' => [
                        asset('images/vehicles/car4.jpg'),
                        asset('images/vehicles/car1.jpg'),
                        asset('images/vehicles/bmw.jpg'),
                        asset('images/vehicles/car2.jpg'),
                    ]
                ],

                7 => [
                    'id' => 7,
                    'name' => 'BMW Convertible',
                    'category' => 'Convertible',
                    'vendor' => 'Premium Rentals',
                    'vendor_rating' => '4.9',
                    'reviews' => 92,
                    'price_per_day' => 220,
                    'location' => 'Biratnagar',
                    'transmission' => 'Automatic',
                    'fuel_type' => 'Gasoline',
                    'seats' => 4,
                    'availability' => 'Available Now',
                    'description' => 'BMW Convertible combines luxury and open-top driving in one premium package. It is suitable for weekend drives, city cruising, and stylish travel.',
                    'features' => [
                        'Convertible Roof',
                        'Leather Seats',
                        'Bluetooth Connectivity',
                        'Rear Camera',
                        'Premium Sound System',
                        'ABS Safety System',
                        'USB Charging Port',
                        'Touchscreen Display',
                    ],
                    'images' => [
                        asset('images/vehicles/bmw.jpg'),
                        asset('images/vehicles/car1.jpg'),
                        asset('images/vehicles/car2.jpg'),
                        asset('images/vehicles/car3.jpg'),
                    ]
                ],

                8 => [
                    'id' => 8,
                    'name' => 'Family Van',
                    'category' => 'Van',
                    'vendor' => 'Elite Motors',
                    'vendor_rating' => '4.7',
                    'reviews' => 145,
                    'price_per_day' => 90,
                    'location' => 'Biratnagar',
                    'transmission' => 'Automatic',
                    'fuel_type' => 'Diesel',
                    'seats' => 8,
                    'availability' => 'Available Now',
                    'description' => 'The Family Van offers spacious seating, comfort, and reliability for group and family travel. It is ideal for tours, airport drops, and long-distance journeys.',
                    'features' => [
                        'Air Conditioning',
                        'Large Seating Capacity',
                        'Rear Camera',
                        'Large Cargo Space',
                        'ABS Safety System',
                        'USB Charging Port',
                        'Comfort Suspension',
                        'Sliding Doors',
                    ],
                    'images' => [
                        asset('images/vehicles/van.jpg'),
                        asset('images/vehicles/van.jpg'),
                        asset('images/vehicles/car3.jpg'),
                        asset('images/vehicles/car4.jpg'),
                    ]
                ],
            ];

            $vehicle = $vehicles[$id] ?? $vehicles[1];
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2 space-y-8">

                <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-200">
                    <img src="{{ $vehicle['images'][0] }}" alt="{{ $vehicle['name'] }}" class="w-full h-[300px] sm:h-[420px] object-cover">
                    <div class="p-5">
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                            @foreach ($vehicle['images'] as $image)
                                <div class="rounded-xl overflow-hidden border border-gray-200">
                                    <img src="{{ $image }}" alt="{{ $vehicle['name'] }}" class="w-full h-24 object-cover">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">{{ $vehicle['name'] }}</h1>
                            <p class="text-gray-500 mt-2">Provided by {{ $vehicle['vendor'] }}</p>

                            <div class="flex flex-wrap gap-3 mt-4">
                                <span class="bg-blue-100 text-blue-700 text-sm font-medium px-4 py-2 rounded-full">
                                    {{ $vehicle['category'] }}
                                </span>
                                <span class="bg-green-100 text-green-700 text-sm font-medium px-4 py-2 rounded-full">
                                    {{ $vehicle['availability'] }}
                                </span>
                                <span class="bg-yellow-100 text-yellow-700 text-sm font-medium px-4 py-2 rounded-full">
                                    Rating: {{ $vehicle['vendor_rating'] }}/5
                                </span>
                            </div>
                        </div>

                        <div class="bg-gray-50 border border-gray-200 rounded-2xl px-6 py-5 min-w-[220px]">
                            <p class="text-sm text-gray-500">Daily Pricing</p>
                            <p class="mt-2 text-4xl font-bold text-blue-600">
                                ${{ $vehicle['price_per_day'] }}
                                <span class="text-lg font-medium text-gray-500">/day</span>
                            </p>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-3">Description</h2>
                        <p class="text-gray-600 leading-7">
                            {{ $vehicle['description'] }}
                        </p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-5">Vehicle Specifications</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                            <p class="text-sm text-gray-500">Transmission</p>
                            <p class="mt-2 font-semibold text-gray-900">{{ $vehicle['transmission'] }}</p>
                        </div>

                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                            <p class="text-sm text-gray-500">Fuel Type</p>
                            <p class="mt-2 font-semibold text-gray-900">{{ $vehicle['fuel_type'] }}</p>
                        </div>

                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                            <p class="text-sm text-gray-500">Seats</p>
                            <p class="mt-2 font-semibold text-gray-900">{{ $vehicle['seats'] }}</p>
                        </div>

                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                            <p class="text-sm text-gray-500">Location</p>
                            <p class="mt-2 font-semibold text-gray-900">{{ $vehicle['location'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-5">Features</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach ($vehicle['features'] as $feature)
                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-gray-700">
                                {{ $feature }}
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-5">Vendor Information</h2>

                    <div class="space-y-3">
                        <p class="text-gray-900 font-semibold">{{ $vehicle['vendor'] }}</p>
                        <p class="text-gray-600">Vendor Rating: {{ $vehicle['vendor_rating'] }}/5</p>
                        <p class="text-gray-600">{{ $vehicle['reviews'] }} reviews</p>
                        <p class="text-gray-600">Location: {{ $vehicle['location'] }}</p>
                    </div>
                </div>

            </div>

            <div>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sticky top-24">
                    <h2 class="text-2xl font-semibold text-gray-900">Book This Vehicle</h2>
                    <p class="text-gray-500 mt-2">Reserve this vehicle and continue to the booking page.</p>

                    <div class="mt-6 space-y-4">
                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                            <p class="text-sm text-gray-500">Price Per Day</p>
                            <p class="mt-2 text-2xl font-bold text-blue-600">${{ $vehicle['price_per_day'] }}/day</p>
                        </div>

                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                            <p class="text-sm text-gray-500">Availability</p>
                            <p class="mt-2 font-semibold text-gray-900">{{ $vehicle['availability'] }}</p>
                        </div>

                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                            <p class="text-sm text-gray-500">Vehicle Type</p>
                            <p class="mt-2 font-semibold text-gray-900">{{ $vehicle['category'] }}</p>
                        </div>
                    </div>

                    <div class="mt-6 space-y-3">
                        <a href="{{ route('bookings.create', $vehicle['id']) }}"
                           class="block w-full text-center bg-black text-white py-4 rounded-xl font-semibold hover:bg-gray-800 transition">
                            Book Now
                        </a>

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