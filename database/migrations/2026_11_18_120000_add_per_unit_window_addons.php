<?php

use App\Models\Booking;
use App\Support\ServiceSnapshot;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Window pricing goes per unit: R50 per window (inside & out), R80 per folding/stacking glass door panel and
 * R80 per sliding glass door panel. Add-ons with a unit are priced per item and the customer enters how many.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_addons', function (Blueprint $table) {
            // Set for per-item add-ons ("window", "panel"); null means a flat one-off price.
            $table->string('unit_label', 50)->nullable()->after('price');
        });

        Schema::table('bookings', function (Blueprint $table) {
            // How many of each per-item add-on was booked, keyed by add-on key.
            $table->json('addon_quantities')->nullable()->after('addons');
        });

        // Bookings that already chose the old flat R200 "Interior windows" add-on keep that price on their
        // quote and invoice: freeze their scope now, before the add-on is repriced.
        Booking::query()->whereNotNull('addons')->get()
            ->filter(fn (Booking $booking) => in_array('windows', $booking->addons ?? [], true))
            ->each(fn (Booking $booking) => ServiceSnapshot::ensureStored($booking));

        $windowsOrder = DB::table('service_addons')->where('key', 'windows')->value('sort_order') ?? 0;

        DB::table('service_addons')->where('sort_order', '>', $windowsOrder)->increment('sort_order', 2);

        DB::table('service_addons')->updateOrInsert(['key' => 'windows'], [
            'label' => 'Windows (inside & out)',
            'price' => 50,
            'unit_label' => 'window',
            'description' => 'Glass cleaned inside and out, with frames and sills.',
            'sort_order' => $windowsOrder,
            'updated_at' => now(),
        ]);

        foreach ([
            ['key' => 'stacking-door-panels', 'label' => 'Folding / stacking glass doors', 'description' => 'Per glass panel, inside and out.', 'sort_order' => $windowsOrder + 1],
            ['key' => 'sliding-door-panels', 'label' => 'Sliding glass doors', 'description' => 'Per glass panel, inside and out, tracks included.', 'sort_order' => $windowsOrder + 2],
        ] as $addon) {
            DB::table('service_addons')->updateOrInsert(['key' => $addon['key']], [
                ...$addon,
                'price' => 80,
                'unit_label' => 'panel',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // The Window Cleaning service shows "From R… / unit" on area pages; it now starts at R50 per window.
        DB::table('services')->where('slug', 'window-cleaning')->update([
            'base_price' => 50,
            'unit_label' => 'window',
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('services')->where('slug', 'window-cleaning')->update(['base_price' => 1400, 'unit_label' => 'property']);

        DB::table('service_addons')->whereIn('key', ['stacking-door-panels', 'sliding-door-panels'])->delete();
        DB::table('service_addons')->where('key', 'windows')->update([
            'label' => 'Interior windows',
            'price' => 200,
            'description' => 'All interior glass, frames and sills.',
        ]);

        Schema::table('bookings', fn (Blueprint $table) => $table->dropColumn('addon_quantities'));
        Schema::table('service_addons', fn (Blueprint $table) => $table->dropColumn('unit_label'));
    }
};
