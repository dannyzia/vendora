<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run core seeders
        $this->call([
            AdminSeeder::class,
            CategorySeeder::class,
            SubscriptionPlanSeeder::class,
        ]);

        $this->command->info('🎉 Database seeding completed successfully!');
    }
}