<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            DiklatPeriodSeeder::class,
            DiklatRegistrationSeeder::class,
            BoardMemberSeeder::class,
            BoardMemberUserSeeder::class,
        ]);
    }
}
