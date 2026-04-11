<?php

namespace Database\Factories;

use App\Enums\VehicleType;
use App\Models\User;
use App\Models\Vehicles;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Vehicles>
 */
class VehiclesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'type' => fake()->randomElement(VehicleType::cases()),
            'category' => fake()->word(),
            'price_per_day' => fake()->randomFloat(2, 20, 150),
            'rating' => fake()->randomFloat(1, 1, 5),
            'reviews' => fake()->numberBetween(0, 50),
            'image' => fake()->imageUrl(),
            'features' => fake()->randomElements(['AC', 'GPS', 'Bluetooth', 'Sunroof', 'Backup Camera', 'USB', '360 Camera'], fake()->numberBetween(2, 5)),
            'description' => fake()->sentence(),
            'seats' => fake()->randomElement([2, 4, 5, 7, 8]),
            'transmission' => fake()->randomElement(['Automatic', 'Manual']),
            'fuel' => fake()->randomElement(['Petrol', 'Diesel', 'Electric', 'Hybrid']),
            'vendor_id' => User::factory(),
            'available' => fake()->boolean(),
        ];
    }
}
