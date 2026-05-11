<?php

namespace Database\Seeders;

use App\Enums\BookingStatus;
use App\Models\Bookings;
use App\Models\User;
use App\Models\Vehicles;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class BookingsSeeder extends Seeder
{
    public function run(): void
    {
        $customer = User::where('email', 'customer@example.com')->firstOrFail();

        $bookings = [
            [
                'vehicle' => 'Eicher Skyline',
                'status' => BookingStatus::CONFIRMED,
                'start_date' => '2026-05-12',
                'end_date' => '2026-05-13',
                'pickup_time' => '9:00 AM',
                'total_price' => 15000,
                'booked_date' => '2026-05-10',
                'special_request' => 'Need early pickup confirmation',
            ],
            [
                'vehicle' => 'Suzuki Avenis 125',
                'status' => BookingStatus::CONFIRMED,
                'start_date' => '2026-05-05',
                'end_date' => '2026-05-06',
                'pickup_time' => '11:00 AM',
                'total_price' => 600,
                'booked_date' => '2026-05-05',
                'special_request' => 'Customer requested pickup confirmation',
            ],
            [
                'vehicle' => 'Honda CRF300L',
                'status' => BookingStatus::PENDING,
                'start_date' => '2026-05-01',
                'end_date' => '2026-05-02',
                'pickup_time' => '9:00 AM',
                'total_price' => 1500,
                'booked_date' => '2026-05-01',
                'special_request' => 'Customer requested helmet and early pickup',
            ],
            [
                'vehicle' => 'Yamaha RayZR 125 Fi Hybrid',
                'status' => BookingStatus::CONFIRMED,
                'start_date' => '2026-05-02',
                'end_date' => '2026-05-04',
                'pickup_time' => '10:00 AM',
                'total_price' => 1100,
                'booked_date' => '2026-05-02',
                'special_request' => 'Customer requested fuel-efficient scooter for city travel',
            ],
        ];

        foreach ($bookings as $data) {
            $vehicle = Vehicles::where('name', $data['vehicle'])->first();

            if (! $vehicle) {
                continue;
            }

            $booking = Bookings::updateOrCreate(
                [
                    'vehicle_id' => $vehicle->id,
                    'customer_id' => $customer->id,
                    'start_date' => $data['start_date'],
                    'end_date' => $data['end_date'],
                ],
                [
                    'vendor_id' => $vehicle->vendor_id,
                    'pickup_time' => $data['pickup_time'],
                    'full_name' => $customer->name,
                    'phone' => '9800000010',
                    'email' => $customer->email,
                    'citizenship_id' => '1234567890',
                    'total_price' => $data['total_price'],
                    'status' => $data['status'],
                    'special_request' => $data['special_request'],
                ]
            );

            $bookedAt = Carbon::parse($data['booked_date']);

            $booking->forceFill([
                'created_at' => $bookedAt,
                'updated_at' => $bookedAt,
            ])->save();
        }
    }
}