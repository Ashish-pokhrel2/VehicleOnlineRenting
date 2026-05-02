<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'premium@rentals.test'],
            [
                'name' => 'Premium Rentals',
                'phone' => '9800000001',
                'country_code' => 'NP',
                'role' => UserRole::VENDOR,
                'password' => Hash::make('password'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'elite@motors.test'],
            [
                'name' => 'Elite Motors',
                'phone' => '9800000002',
                'country_code' => 'NP',
                'role' => UserRole::VENDOR,
                'password' => Hash::make('password'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'urban@mobility.test'],
            [
                'name' => 'Urban Mobility',
                'phone' => '9800000003',
                'country_code' => 'NP',
                'role' => UserRole::VENDOR,
                'password' => Hash::make('password'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Demo Customer',
                'phone' => '9800000010',
                'country_code' => 'NP',
                'role' => UserRole::CUSTOMER,
                'password' => Hash::make('password'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'phone' => '9800000000',
                'country_code' => 'NP',
                'role' => UserRole::ADMIN,
                'password' => Hash::make('password'),
            ]
        );
    }
}
