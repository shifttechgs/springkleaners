<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CheckAdminAccount extends Command
{
    protected $signature = 'admin:check {email}';

    protected $description = 'Show a user\'s id/name/email/role without exposing the password';

    public function handle(): void
    {
        $user = User::where('email', $this->argument('email'))->first();

        if (! $user) {
            $this->error('No user found with that email.');

            return;
        }

        $this->info("id={$user->id} name={$user->name} email={$user->email} role={$user->role->value} has_password=".($user->password ? 'yes' : 'no')." notify_new_bookings=".($user->notify_new_bookings ? 'true' : 'false'));
    }
}
