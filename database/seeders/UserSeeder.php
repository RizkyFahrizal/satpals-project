<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@satpals.com',
            'password' => Hash::make('password123'),
            'role' => User::ROLE_SUPER_ADMIN,
            'email_verified_at' => now(),
        ]);

        // Pengurus - Rizal
        User::create([
            'name' => 'Rizal',
            'email' => 'rizal@satpals.com',
            'password' => Hash::make('password123'),
            'role' => User::ROLE_PENGURUS,
            'email_verified_at' => now(),
        ]);

        // Public - Zhilan
        User::create([
            'name' => 'Zhilan',
            'email' => 'zhilan@satpals.com',
            'password' => Hash::make('password123'),
            'role' => User::ROLE_PUBLIC,
            'email_verified_at' => now(),
        ]);
    }
}
