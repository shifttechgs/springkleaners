<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/*
 * Quotes and invoices issued before scope snapshots existed carry no frozen
 * copy, so they fall back to rendering the service as it reads today. Freeze
 * the current wording onto them now: their documents keep showing what they
 * already show, but stop drifting the next time a service is reworded.
 *
 * Written against the tables directly rather than App\Support\ServiceSnapshot
 * so the backfill stays fixed even if that class changes later.
 */
return new class extends Migration
{
    public function up(): void
    {
        $services = DB::table('services')
            ->get(['slug', 'name', 'description', 'inclusions', 'avg_hours', 'unit_label'])
            ->keyBy('slug');

        $addons = DB::table('service_addons')->get(['key', 'label', 'price', 'description'])->keyBy('key');

        DB::table('bookings')
            ->whereNull('service_snapshot')
            ->where(fn ($q) => $q->whereNotNull('quote_sent_at')->orWhereNotNull('invoice_number'))
            ->orderBy('id')
            ->chunkById(200, function ($bookings) use ($services, $addons) {
                foreach ($bookings as $booking) {
                    $service = $services->get($booking->service);

                    $selected = collect(json_decode($booking->addons ?? '[]', true) ?: [])
                        ->map(fn ($key) => $addons->get($key))
                        ->filter()
                        ->map(fn ($addon) => [
                            'key' => $addon->key,
                            'label' => $addon->label,
                            'price' => (float) $addon->price,
                            'description' => $addon->description,
                        ])
                        ->values()
                        ->all();

                    DB::table('bookings')->where('id', $booking->id)->update([
                        'service_snapshot' => json_encode([
                            'name' => $service->name ?? $booking->service,
                            'description' => $service->description ?? null,
                            'inclusions' => json_decode($service->inclusions ?? '[]', true) ?: [],
                            'avg_hours' => $service->avg_hours ?? null,
                            'unit_label' => $service->unit_label ?? null,
                            'addons' => $selected,
                        ]),
                    ]);
                }
            });
    }

    public function down(): void
    {
        // Nothing to restore — these bookings simply fall back to live copy again.
    }
};
