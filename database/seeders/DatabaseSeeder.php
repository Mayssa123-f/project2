<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         echo "Starting to seed passengers...\n";
    \App\Models\Passenger::factory()->count(490)->create();
    echo "Finished seeding.\n";
    }
    
}
