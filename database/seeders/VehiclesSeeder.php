<?php

namespace Database\Seeders;

use App\Enums\VehicleType;
use App\Models\User;
use App\Models\Vehicles;
use Illuminate\Database\Seeder;

class VehiclesSeeder extends Seeder
{
    public function run(): void
    {
        $vendors = User::whereIn('email', [
            'premium@rentals.test',
            'elite@motors.test',
            'urban@mobility.test',
        ])->get()->keyBy('email');

        $vehicles = [

            // ================= CARS =================

            [
                'name'=>'Hyundai Creta',
                'type'=>VehicleType::CAR,
                'category'=>'SUV',
                'vendor_email'=>'premium@rentals.test',
                'rating'=>4.8,
                'reviews'=>120,
                'price_per_day'=>2200,
                'location'=>'Biratnagar',
                'transmission'=>'Automatic',
                'fuel'=>'Petrol',
                'seats'=>5,
                'description'=>'Comfortable SUV suitable for family trips and long-distance travel.',
                'features'=>[
                    'Air Conditioning',
                    'Bluetooth Connectivity',
                    'Rear Camera',
                    'GPS Navigation',
                    'ABS Safety System'
                ],
                'images'=>[
                    'images/vehicles/car1.jpg'
                ],
            ],

            [
                'name'=>'Maruti Suzuki Celerio',
                'type'=>VehicleType::CAR,
                'category'=>'Hatchback',
                'vendor_email'=>'urban@mobility.test',
                'rating'=>4.6,
                'reviews'=>80,
                'price_per_day'=>1200,
                'location'=>'Biratnagar',
                'transmission'=>'Automatic',
                'fuel'=>'Petrol',
                'seats'=>5,
                'description'=>'Compact and fuel-efficient hatchback ideal for city travel.',
                'features'=>[
                    'Air Conditioning',
                    'Bluetooth',
                    'USB Charging',
                    'ABS',
                    'Fuel Efficient'
                ],
                'images'=>[
                    'images/vehicles/car2.jpg'
                ],
            ],

            [
                'name'=>'Hyundai Exter',
                'type'=>VehicleType::CAR,
                'category'=>'Compact SUV',
                'vendor_email'=>'elite@motors.test',
                'rating'=>4.7,
                'reviews'=>92,
                'price_per_day'=>1500,
                'location'=>'Biratnagar',
                'transmission'=>'Automatic',
                'fuel'=>'Petrol',
                'seats'=>5,
                'description'=>'Compact SUV designed for practicality and comfort.',
                'features'=>[
                    'Air Conditioning',
                    'Rear Camera',
                    'GPS',
                    'ABS',
                    'Bluetooth'
                ],
                'images'=>[
                    'images/vehicles/car3.jpg'
                ],
            ],

            [
                'name'=>'Tata Harrier',
                'type'=>VehicleType::CAR,
                'category'=>'SUV',
                'vendor_email'=>'premium@rentals.test',
                'rating'=>4.9,
                'reviews'=>110,
                'price_per_day'=>2500,
                'location'=>'Biratnagar',
                'transmission'=>'Automatic',
                'fuel'=>'Diesel',
                'seats'=>5,
                'description'=>'Premium SUV suitable for long trips and premium travel.',
                'features'=>[
                    'Air Conditioning',
                    'Leather Seats',
                    'GPS',
                    'Rear Camera',
                    '4WD'
                ],
                'images'=>[
                    'images/vehicles/car4.jpg'
                ],
            ],

            [
                'name'=>'Hyundai Grand i10 Nios',
                'type'=>VehicleType::CAR,
                'category'=>'Hatchback',
                'vendor_email'=>'urban@mobility.test',
                'rating'=>4.5,
                'reviews'=>75,
                'price_per_day'=>1000,
                'location'=>'Biratnagar',
                'transmission'=>'Manual',
                'fuel'=>'Petrol',
                'seats'=>5,
                'description'=>'Affordable hatchback suitable for daily travel.',
                'features'=>[
                    'Air Conditioning',
                    'Bluetooth',
                    'USB Charging',
                    'Comfort Seats'
                ],
                'images'=>[
                    'images/vehicles/car5.jpg'
                ],
            ],

            // ================= BIKES =================

            [
                'name'=>'Royal Enfield Hunter 350',
                'type'=>VehicleType::BIKE,
                'category'=>'Cruiser',
                'vendor_email'=>'elite@motors.test',
                'rating'=>4.8,
                'reviews'=>95,
                'price_per_day'=>1200,
                'location'=>'Dharan',
                'transmission'=>'Manual',
                'fuel'=>'Petrol',
                'seats'=>2,
                'description'=>'Classic cruiser bike for comfortable riding.',
                'features'=>[
                    'Disc Brakes',
                    'Electric Start',
                    'Digital Meter',
                    'LED Headlamp'
                ],
                'images'=>[
                    'images/vehicles/bike1.jpg'
                ],
            ],

            [
                'name'=>'CFMoto 250NK',
                'type'=>VehicleType::BIKE,
                'category'=>'Naked Bike',
                'vendor_email'=>'premium@rentals.test',
                'rating'=>4.6,
                'reviews'=>70,
                'price_per_day'=>1000,
                'location'=>'Dharan',
                'transmission'=>'Manual',
                'fuel'=>'Petrol',
                'seats'=>2,
                'description'=>'Sporty naked bike with modern performance.',
                'features'=>[
                    'ABS',
                    'Digital Display',
                    'Disc Brakes'
                ],
                'images'=>[
                    'images/vehicles/bike2.jpg'
                ],
            ],

            [
                'name'=>'Honda CRF300L',
                'type'=>VehicleType::BIKE,
                'category'=>'Adventure',
                'vendor_email'=>'elite@motors.test',
                'rating'=>4.8,
                'reviews'=>82,
                'price_per_day'=>1500,
                'location'=>'Dharan',
                'transmission'=>'Manual',
                'fuel'=>'Petrol',
                'seats'=>2,
                'description'=>'Adventure bike suitable for off-road riding.',
                'features'=>[
                    'Long Travel Suspension',
                    'ABS',
                    'LED Headlamp'
                ],
                'images'=>[
                    'images/vehicles/bike3.jpg'
                ],
            ],

            [
                'name'=>'KTM 390 Adventure',
                'type'=>VehicleType::BIKE,
                'category'=>'Adventure',
                'vendor_email'=>'premium@rentals.test',
                'rating'=>4.9,
                'reviews'=>101,
                'price_per_day'=>1800,
                'location'=>'Dharan',
                'transmission'=>'Manual',
                'fuel'=>'Petrol',
                'seats'=>2,
                'description'=>'Powerful adventure bike for touring.',
                'features'=>[
                    'ABS',
                    'GPS Mount',
                    'Adventure Suspension'
                ],
                'images'=>[
                    'images/vehicles/bike4.jpg'
                ],
            ],

            [
                'name'=>'Suzuki GSX-S750',
                'type'=>VehicleType::BIKE,
                'category'=>'Sports Naked',
                'vendor_email'=>'urban@mobility.test',
                'rating'=>4.8,
                'reviews'=>88,
                'price_per_day'=>1600,
                'location'=>'Dharan',
                'transmission'=>'Manual',
                'fuel'=>'Petrol',
                'seats'=>2,
                'description'=>'Sport naked bike with aggressive styling.',
                'features'=>[
                    'Sport Mode',
                    'ABS',
                    'Digital Meter'
                ],
                'images'=>[
                    'images/vehicles/bike5.jpg'
                ],
            ],

            // ================= SCOOTERS =================

            [
                'name'=>'Honda Dio 125',
                'type'=>VehicleType::SCOOTER,
                'category'=>'Commuter Scooter',
                'vendor_email'=>'urban@mobility.test',
                'rating'=>4.5,
                'reviews'=>130,
                'price_per_day'=>500,
                'location'=>'Itahari',
                'transmission'=>'Automatic',
                'fuel'=>'Petrol',
                'seats'=>2,
                'description'=>'Easy and practical commuter scooter.',
                'features'=>[
                    'Automatic',
                    'Fuel Efficient',
                    'Electric Start'
                ],
                'images'=>[
                    'images/vehicles/scooter1.jpg'
                ],
            ],

            [
                'name'=>'TVS Ntorq 125',
                'type'=>VehicleType::SCOOTER,
                'category'=>'Sports Scooter',
                'vendor_email'=>'urban@mobility.test',
                'rating'=>4.6,
                'reviews'=>115,
                'price_per_day'=>600,
                'location'=>'Itahari',
                'transmission'=>'Automatic',
                'fuel'=>'Petrol',
                'seats'=>2,
                'description'=>'Sporty scooter for city riding.',
                'features'=>[
                    'Disc Brakes',
                    'Digital Display'
                ],
                'images'=>[
                    'images/vehicles/scooter2.jpg'
                ],
            ],

            [
                'name'=>'Yamaha RayZR 125 Fi Hybrid',
                'type'=>VehicleType::SCOOTER,
                'category'=>'Hybrid Scooter',
                'vendor_email'=>'elite@motors.test',
                'rating'=>4.5,
                'reviews'=>105,
                'price_per_day'=>550,
                'location'=>'Itahari',
                'transmission'=>'Automatic',
                'fuel'=>'Hybrid',
                'seats'=>2,
                'description'=>'Fuel efficient hybrid scooter.',
                'features'=>[
                    'Hybrid Assist',
                    'Automatic'
                ],
                'images'=>[
                    'images/vehicles/scooter3.jpg'
                ],
            ],

            [
                'name'=>'Aprilia SR 160',
                'type'=>VehicleType::SCOOTER,
                'category'=>'Sports Scooter',
                'vendor_email'=>'premium@rentals.test',
                'rating'=>4.7,
                'reviews'=>95,
                'price_per_day'=>700,
                'location'=>'Itahari',
                'transmission'=>'Automatic',
                'fuel'=>'Petrol',
                'seats'=>2,
                'description'=>'Premium sports scooter.',
                'features'=>[
                    'Disc Brakes',
                    'Sport Design'
                ],
                'images'=>[
                    'images/vehicles/scooter4.jpg'
                ],
            ],

            [
                'name'=>'Suzuki Avenis 125',
                'type'=>VehicleType::SCOOTER,
                'category'=>'Commuter Scooter',
                'vendor_email'=>'elite@motors.test',
                'rating'=>4.6,
                'reviews'=>100,
                'price_per_day'=>600,
                'location'=>'Itahari',
                'transmission'=>'Automatic',
                'fuel'=>'Petrol',
                'seats'=>2,
                'description'=>'Practical city commuter scooter.',
                'features'=>[
                    'Automatic',
                    'Fuel Efficient'
                ],
                'images'=>[
                    'images/vehicles/scooter5.jpg'
                ],
            ],

            // ================= BUSES =================

            [
                'name'=>'Tata Star Bus',
                'type'=>VehicleType::BUS,
                'category'=>'Standard Bus',
                'vendor_email'=>'elite@motors.test',
                'rating'=>4.7,
                'reviews'=>65,
                'price_per_day'=>10000,
                'location'=>'Biratnagar',
                'transmission'=>'Manual',
                'fuel'=>'Diesel',
                'seats'=>35,
                'description'=>'Reliable standard bus for group travel.',
                'features'=>[
                    'Air Conditioning',
                    'Large Seating Capacity'
                ],
                'images'=>[
                    'images/vehicles/bus1.jpg'
                ],
            ],

            [
                'name'=>'Tata Magna',
                'type'=>VehicleType::BUS,
                'category'=>'City Bus',
                'vendor_email'=>'urban@mobility.test',
                'rating'=>4.6,
                'reviews'=>58,
                'price_per_day'=>12000,
                'location'=>'Biratnagar',
                'transmission'=>'Manual',
                'fuel'=>'Diesel',
                'seats'=>40,
                'description'=>'Spacious city bus.',
                'features'=>[
                    'Comfort Seating',
                    'Air Conditioning'
                ],
                'images'=>[
                    'images/vehicles/bus2.jpg'
                ],
            ],

            [
                'name'=>'Eicher Skyline',
                'type'=>VehicleType::BUS,
                'category'=>'Tourist Bus',
                'vendor_email'=>'premium@rentals.test',
                'rating'=>4.8,
                'reviews'=>72,
                'price_per_day'=>15000,
                'location'=>'Biratnagar',
                'transmission'=>'Manual',
                'fuel'=>'Diesel',
                'seats'=>32,
                'description'=>'Comfortable tourist coach.',
                'features'=>[
                    'Air Conditioning',
                    'Large Cargo'
                ],
                'images'=>[
                    'images/vehicles/bus3.jpg'
                ],
            ],

            [
                'name'=>'Tata LPO 1515',
                'type'=>VehicleType::BUS,
                'category'=>'Standard Bus',
                'vendor_email'=>'elite@motors.test',
                'rating'=>4.6,
                'reviews'=>60,
                'price_per_day'=>10000,
                'location'=>'Biratnagar',
                'transmission'=>'Manual',
                'fuel'=>'Diesel',
                'seats'=>40,
                'description'=>'Dependable standard bus.',
                'features'=>[
                    'Comfort Suspension',
                    'ABS'
                ],
                'images'=>[
                    'images/vehicles/bus4.jpg'
                ],
            ],

            [
                'name'=>'Ashok Leyland Viking',
                'type'=>VehicleType::BUS,
                'category'=>'Large Coach',
                'vendor_email'=>'premium@rentals.test',
                'rating'=>4.9,
                'reviews'=>78,
                'price_per_day'=>18000,
                'location'=>'Biratnagar',
                'transmission'=>'Manual',
                'fuel'=>'Diesel',
                'seats'=>45,
                'description'=>'Large coach for long-distance travel.',
                'features'=>[
                    'Air Conditioning',
                    'Large Seating Capacity'
                ],
                'images'=>[
                    'images/vehicles/bus5.jpg'
                ],
            ],

        ];

        foreach ($vehicles as $vehicle) {

            $vendorId = $vendors[$vehicle['vendor_email']]->id;
            $images = $vehicle['images'];

            Vehicles::updateOrCreate(
                [
                    'name'=>$vehicle['name'],
                    'vendor_id'=>$vendorId,
                ],
                [
                    'name'=>$vehicle['name'],
                    'type'=>$vehicle['type'],
                    'category'=>$vehicle['category'],
                    'location'=>$vehicle['location'],
                    'price_per_day'=>$vehicle['price_per_day'],
                    'rating'=>$vehicle['rating'],
                    'reviews'=>$vehicle['reviews'],
                    'image'=>$images[0] ?? null,
                    'images'=>$images,
                    'features'=>$vehicle['features'],
                    'description'=>$vehicle['description'],
                    'seats'=>$vehicle['seats'],
                    'transmission'=>$vehicle['transmission'],
                    'fuel'=>$vehicle['fuel'],
                    'vendor_id'=>$vendorId,
                    'available'=>true,
                ]
            );
        }
    }
}