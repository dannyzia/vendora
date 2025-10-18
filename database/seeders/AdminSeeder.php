<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeder.
     * Creates the first admin account for the platform.
     */
    public function run(): void
    {
        // Check if admin already exists
        $adminExists = User::where('role', 'admin')
            ->where('email', 'admin@vendora.com')
            ->exists();

        if ($adminExists) {
            $this->command->info('Admin user already exists. Skipping...');
            return;
        }

        // Create admin user
        $admin = User::create([
            'uuid' => (string) Str::uuid(),
            'name' => 'Super Admin',
            'email' => 'admin@vendora.com',
            'phone' => '01700000000',
            'password' => Hash::make('Admin@123456'),
            'role' => 'admin',
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
        ]);

        $this->command->info('✅ Admin user created successfully!');
        $this->command->info('📧 Email: admin@vendora.com');
        $this->command->info('🔑 Password: Admin@123456');
        $this->command->warn('⚠️  Please change this password after first login!');
    }
}