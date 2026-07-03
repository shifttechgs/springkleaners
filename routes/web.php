<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\ClientController as AdminClientController;
use App\Http\Controllers\Admin\CompanySettingController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ExpenseController as AdminExpenseController;
use App\Http\Controllers\Admin\ForgotPasswordController;
use App\Http\Controllers\Admin\FuelSettingController;
use App\Http\Controllers\Admin\InvitationController;
use App\Http\Controllers\Admin\InvoiceController as AdminInvoiceController;
use App\Http\Controllers\Admin\InvoicePdfController;
use App\Http\Controllers\Admin\NotificationPreferenceController;
use App\Http\Controllers\Admin\PasswordController as AdminPasswordController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\QuotePdfController;
use App\Http\Controllers\Admin\ResetPasswordController;
use App\Http\Controllers\Admin\ServiceAddonController as AdminServiceAddonController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ClientInvoiceController;
use App\Http\Controllers\ClientQuoteController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/services/{slug}', [ServiceController::class, 'show'])->name('services.show');
Route::get('/areas/{slug}', [LocationController::class, 'show'])->name('areas.show');

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

Route::get('/quote/{booking:accepted_token}', [ClientQuoteController::class, 'show'])->name('quote.show');
Route::post('/quote/{booking:accepted_token}/accept', [ClientQuoteController::class, 'accept'])->name('quote.accept');
Route::post('/quote/{booking:accepted_token}/decline', [ClientQuoteController::class, 'decline'])->name('quote.decline');

Route::get('/invoice/{booking:accepted_token}', [ClientInvoiceController::class, 'show'])->name('invoice.show');
Route::get('/invoice/{booking:accepted_token}/download', [ClientInvoiceController::class, 'download'])->name('invoice.download');

Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('login.attempt')->middleware('throttle:5,1');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('logout');

Route::get('/admin/forgot-password', [ForgotPasswordController::class, 'show'])->name('password.request');
Route::post('/admin/forgot-password', [ForgotPasswordController::class, 'send'])->name('password.email')->middleware('throttle:5,1');
Route::get('/admin/reset-password/{token}', [ResetPasswordController::class, 'show'])->name('password.reset');
Route::post('/admin/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update')->middleware('throttle:5,1');

Route::get('/admin/invite/{user}', [InvitationController::class, 'accept'])->name('admin.invite.accept')->middleware('signed');
Route::post('/admin/invite/{user}', [InvitationController::class, 'store'])->name('admin.invite.store')->middleware('signed');

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [AdminBookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [AdminBookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::patch('/bookings/{booking}', [AdminBookingController::class, 'update'])->name('bookings.update');
    Route::post('/bookings/{booking}/send-quote', [AdminBookingController::class, 'sendQuote'])->name('bookings.send-quote');
    Route::get('/bookings/{booking}/quote-pdf', [QuotePdfController::class, 'download'])->name('bookings.quote-pdf');
    Route::get('/bookings/{booking}/quote-pdf/preview', [QuotePdfController::class, 'preview'])->name('bookings.quote-pdf.preview');
    Route::get('/bookings/{booking}/invoice-pdf', [InvoicePdfController::class, 'download'])->name('bookings.invoice-pdf');
    Route::get('/bookings/{booking}/invoice-pdf/preview', [InvoicePdfController::class, 'preview'])->name('bookings.invoice-pdf.preview');
    Route::post('/bookings/{booking}/mark-deposit-paid', [AdminBookingController::class, 'markDepositPaid'])->name('bookings.mark-deposit-paid');
    Route::post('/bookings/{booking}/mark-paid', [AdminBookingController::class, 'markPaid'])->name('bookings.mark-paid');
    Route::post('/bookings/{booking}/send-invoice-email', [AdminBookingController::class, 'sendInvoiceEmail'])->name('bookings.send-invoice-email');
    Route::post('/bookings/{booking}/send-confirmation-email', [AdminBookingController::class, 'sendConfirmationEmail'])->name('bookings.send-confirmation-email');
    Route::post('/bookings/{booking}/send-thank-you-email', [AdminBookingController::class, 'sendThankYouEmail'])->name('bookings.send-thank-you-email');

    Route::get('/invoices', [AdminInvoiceController::class, 'index'])->name('invoices.index');

    Route::get('/clients', [AdminClientController::class, 'index'])->name('clients.index');
    Route::get('/clients/{client}', [AdminClientController::class, 'show'])->name('clients.show');

    Route::get('/expenses', [AdminExpenseController::class, 'index'])->name('expenses.index');
    Route::get('/expenses/create', [AdminExpenseController::class, 'create'])->name('expenses.create');
    Route::post('/expenses', [AdminExpenseController::class, 'store'])->name('expenses.store');
    Route::get('/expenses/{expense}/edit', [AdminExpenseController::class, 'edit'])->name('expenses.edit');
    Route::put('/expenses/{expense}', [AdminExpenseController::class, 'update'])->name('expenses.update');
    Route::delete('/expenses/{expense}', [AdminExpenseController::class, 'destroy'])->name('expenses.destroy');

    Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');

    Route::get('/password', [AdminPasswordController::class, 'edit'])->name('password.edit');
    Route::put('/password', [AdminPasswordController::class, 'update'])->name('password.update');

    Route::get('/notifications', [NotificationPreferenceController::class, 'edit'])->name('notifications.edit');
    Route::put('/notifications', [NotificationPreferenceController::class, 'update'])->name('notifications.update');

    Route::get('/fuel-settings', [FuelSettingController::class, 'edit'])->name('fuel-settings.edit');
    Route::put('/fuel-settings', [FuelSettingController::class, 'update'])->name('fuel-settings.update');

    Route::get('/company-settings', [CompanySettingController::class, 'edit'])->name('company-settings.edit');
    Route::put('/company-settings', [CompanySettingController::class, 'update'])->name('company-settings.update');

    Route::middleware('admin')->group(function () {
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
        Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
        Route::post('/users/{user}/resend-invite', [AdminUserController::class, 'resendInvite'])->name('users.resend-invite');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

        Route::get('/services', [AdminServiceController::class, 'index'])->name('services.index');
        Route::get('/services/create', [AdminServiceController::class, 'create'])->name('services.create');
        Route::post('/services', [AdminServiceController::class, 'store'])->name('services.store');
        Route::get('/services/{service}/edit', [AdminServiceController::class, 'edit'])->name('services.edit');
        Route::put('/services/{service}', [AdminServiceController::class, 'update'])->name('services.update');
        Route::delete('/services/{service}', [AdminServiceController::class, 'destroy'])->name('services.destroy');

        Route::get('/service-addons/create', [AdminServiceAddonController::class, 'create'])->name('service-addons.create');
        Route::post('/service-addons', [AdminServiceAddonController::class, 'store'])->name('service-addons.store');
        Route::get('/service-addons/{serviceAddon}/edit', [AdminServiceAddonController::class, 'edit'])->name('service-addons.edit');
        Route::put('/service-addons/{serviceAddon}', [AdminServiceAddonController::class, 'update'])->name('service-addons.update');
        Route::delete('/service-addons/{serviceAddon}', [AdminServiceAddonController::class, 'destroy'])->name('service-addons.destroy');
    });
});
