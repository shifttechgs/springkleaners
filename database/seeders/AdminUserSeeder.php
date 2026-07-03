<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = 'shifttechgs1@gmail.com';

        if (User::where('email', $email)->exists()) {
            $this->command?->info("Admin user {$email} already exists — skipping.");

            return;
        }

        $password = Str::password(16);

        User::create([
            'name' => 'SpringKleaners Admin',
            'email' => $email,
            'password' => $password,
            'role' => 'admin',
        ]);

        $this->command?->info('Admin user created:');
        $this->command?->info("  Email:    {$email}");
        $this->command?->info("  Password: {$password}");
        $this->command?->warn('Change this password after first login.');
    }
}
