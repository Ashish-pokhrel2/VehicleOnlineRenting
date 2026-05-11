<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\User;
use App\Models\Vehicles;

class ReviewsSeeder extends Seeder
{
    public function run(): void
    {
        $vendor = User::where('role', 'vendor')->first();
        $customer = User::where('role', 'customer')->first();

        if (!$vendor || !$customer) {
            return;
        }

        $vehicles = Vehicles::where('vendor_id', $vendor->id)->take(3)->get();

        if ($vehicles->count() < 3) {
            return;
        }

        Review::query()->delete();

        Review::create([
            'vendor_id' => $vendor->id,
            'vehicle_id' => $vehicles[0]->id,
            'customer_id' => $customer->id,
            'rating' => 5,
            'comment' => 'Excellent service and vehicle. Highly recommend!',
            'created_at' => now()->subDays(3),
            'updated_at' => now()->subDays(3),
        ]);

        Review::create([
            'vendor_id' => $vendor->id,
            'vehicle_id' => $vehicles[1]->id,
            'customer_id' => $customer->id,
            'rating' => 5,
            'comment' => 'Amazing experience! The vehicle was clean and performed perfectly.',
            'created_at' => now()->subDays(8),
            'updated_at' => now()->subDays(8),
        ]);

        Review::create([
            'vendor_id' => $vendor->id,
            'vehicle_id' => $vehicles[2]->id,
            'customer_id' => $customer->id,
            'rating' => 4,
            'comment' => 'Loved the vehicle and customer service. Pickup was slightly delayed.',
            'created_at' => now()->subDays(14),
            'updated_at' => now()->subDays(14),
        ]);

        Review::create([
            'vendor_id' => $vendor->id,
            'vehicle_id' => $vehicles[0]->id,
            'customer_id' => $customer->id,
            'rating' => 5,
            'comment' => 'Luxury experience from start to finish. Very professional vendor.',
            'created_at' => now()->subDays(25),
            'updated_at' => now()->subDays(25),
        ]);

        Review::create([
            'vendor_id' => $vendor->id,
            'vehicle_id' => $vehicles[1]->id,
            'customer_id' => $customer->id,
            'rating' => 4,
            'comment' => 'Smooth booking process and friendly communication.',
            'created_at' => now()->subDays(35),
            'updated_at' => now()->subDays(35),
        ]);
    }
}