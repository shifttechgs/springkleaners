<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/get-my-quote', function () {
    return view('quote');
});

Route::get('/book/availability', [BookingController::class, 'availability'])->name('booking.availability');
Route::post('/book/reserve', [BookingController::class, 'reserve'])->name('booking.reserve');
Route::get('/book', [BookingController::class, 'show'])->name('booking.show');

// Legacy per-service URLs — redirect into the single booking page with ?service=.
Route::get('/book/{service}', function (string $service) {
    return redirect()->route('booking.show', ['service' => $service]);
});

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/sitemap.xml', function () {
    $posts = collect(config('blog.posts'));

    return response()
        ->view('sitemap', ['posts' => $posts])
        ->header('Content-Type', 'text/xml');
});
