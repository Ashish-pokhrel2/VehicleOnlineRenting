<?php

namespace Database\Seeders;

use App\Models\BookingSetting;
use Illuminate\Database\Seeder;

class BookingSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BookingSetting::updateOrCreate(
            ['id' => 1],
            [
                'service_fee' => 10,
                'default_estimated_days' => 3,
            ]
        );
    }
}
