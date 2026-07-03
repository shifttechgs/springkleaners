<?php

use App\Support\QuoteExpiry;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('bookings:expire-stale-quotes', function () {
    $count = QuoteExpiry::sweep();
    $this->info("Expired {$count} stale booking(s).");
})->purpose('Expire Pending/Quoted bookings past their hold window, freeing the calendar slot');

Schedule::command('bookings:expire-stale-quotes')->daily();
