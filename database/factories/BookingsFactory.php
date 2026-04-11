<?php

namespace Database\Factories;

use App\Enums\BookingStatus;
use App\Models\Bookings;
use App\Models\User;
use App\Models\Vehicles;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Bookings>
 */
class BookingsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('+1 days', '+30 days');
        $endDate = fake()->dateTimeBetween($startDate, '+45 days');
        $vehicle = Vehicles::factory()->create();
        $days = (clone $startDate)->diff($endDate)->days;

        return [
            'vehicle_id' => $vehicle->id,
            'customer_id' => User::factory()->create(),
            'vendor_id' => $vehicle->vendor_id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_price' => $vehicle->price_per_day * max(1, $days),
            'status' => fake()->randomElement(BookingStatus::cases()),
        ];
    }
}
