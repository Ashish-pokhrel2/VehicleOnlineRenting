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
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customer = User::where('email', 'customer@example.com')->firstOrFail();

        $bookings = [
            [
                'vehicle' => 'Mercedes S-Class',
                'status' => BookingStatus::CONFIRMED,
                'start_date' => '2026-03-25',
                'end_date' => '2026-03-28',
                'total_price' => 450,
                'booked_date' => '2026-03-20',
            ],
            [
                'vehicle' => 'Harley Davidson',
                'status' => BookingStatus::PENDING,
                'start_date' => '2026-04-01',
                'end_date' => '2026-04-03',
                'total_price' => 160,
                'booked_date' => '2026-03-22',
            ],
            [
                'vehicle' => 'Range Rover Sport',
                'status' => BookingStatus::COMPLETED,
                'start_date' => '2026-03-15',
                'end_date' => '2026-03-18',
                'total_price' => 540,
                'booked_date' => '2026-03-10',
            ],
        ];

        foreach ($bookings as $data) {
            $vehicle = Vehicles::where('name', $data['vehicle'])->firstOrFail();

            $booking = Bookings::updateOrCreate(
                [
                    'vehicle_id' => $vehicle->id,
                    'customer_id' => $customer->id,
                    'start_date' => $data['start_date'],
                    'end_date' => $data['end_date'],
                ],
                [
                    'vendor_id' => $vehicle->vendor_id,
                    'total_price' => $data['total_price'],
                    'status' => $data['status'],
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
