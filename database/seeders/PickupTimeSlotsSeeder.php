<?php

namespace Database\Seeders;

use App\Models\PickupTimeSlot;
use Illuminate\Database\Seeder;

class PickupTimeSlotsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $slots = [
            ['label' => '9:00 AM', 'sort_order' => 1],
            ['label' => '11:00 AM', 'sort_order' => 2],
            ['label' => '1:00 PM', 'sort_order' => 3],
            ['label' => '3:00 PM', 'sort_order' => 4],
            ['label' => '5:00 PM', 'sort_order' => 5],
        ];

        foreach ($slots as $slot) {
            PickupTimeSlot::updateOrCreate(
                ['label' => $slot['label']],
                ['sort_order' => $slot['sort_order']]
            );
        }
    }
}
