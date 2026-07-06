<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class BookingsAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = 'bookings@springkleaners.co.za';

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => 'SpringKleaners Admin',
                'password' => 'Bu!!etdog@1994',
                'role' => 'admin',
            ]
        );

        $this->command?->info("Admin user ready: {$email}");
    }
}
