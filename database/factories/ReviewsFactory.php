<?php

namespace Database\Factories;

use App\Models\Reviews;
use App\Models\User;
use App\Models\Vehicles;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reviews>
 */
class ReviewsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $vehicle = Vehicles::factory()->create();

        return [
            'vehicle_id' => $vehicle->id,
            'customer_id' => User::factory()->create(),
            'vendor_id' => $vehicle->vendor_id,
            'rating' => fake()->randomFloat(1, 1, 5),
            'comment' => fake()->sentence(10),
        ];
    }
}
