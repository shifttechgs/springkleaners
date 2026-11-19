<?php

namespace App\Support;

use App\Models\Booking;

class ServiceSnapshot
{
    /**
     * Capture the service scope + add-on wording as it stands right now.
     * Written to the booking when the quote is sent so the documents keep
     * saying what the client agreed to, even if the service is reworded later.
     *
     * @return array{name: string, description: ?string, inclusions: array<int, string>, avg_hours: ?int, unit_label: ?string, addons: array<int, array{key: string, label: string, price: float, description: ?string}>}
     */
    public static function build(Booking $booking): array
    {
        $service = Services::find($booking->service) ?? [];
        $addonsList = collect(Services::addons());

        $quantities = $booking->addon_quantities ?? [];

        $addons = collect($booking->addons ?? [])
            ->map(fn ($key) => $addonsList->firstWhere('key', $key))
            ->filter()
            ->map(fn ($addon) => [
                'key' => $addon['key'],
                'label' => $addon['label'],
                // Per-item add-ons keep the unit price here; qty says how many were booked.
                'price' => (float) $addon['price'],
                'unit_label' => $addon['unit'] ?? null,
                'qty' => filled($addon['unit'] ?? null) ? max(1, (int) ($quantities[$addon['key']] ?? 1)) : 1,
                'description' => $addon['desc'] ?? null,
            ])
            ->values()
            ->all();

        return [
            'name' => $service['name'] ?? $booking->service,
            'description' => $service['description'] ?? $service['tagline'] ?? null,
            'inclusions' => array_values($service['inclusions'] ?? []),
            'avg_hours' => $service['avg_hours'] ?? null,
            'unit_label' => $service['unit_label'] ?? null,
            'addons' => $addons,
        ];
    }

    /**
     * The scope to print for a booking: the frozen copy when there is one,
     * otherwise a live build so bookings taken before snapshots existed still
     * show their details.
     *
     * @return array{name: string, description: ?string, inclusions: array<int, string>, avg_hours: ?int, unit_label: ?string, addons: array<int, array{key: string, label: string, price: float, description: ?string}>}
     */
    public static function for(Booking $booking): array
    {
        $snapshot = $booking->service_snapshot;

        if (blank($snapshot) || blank($snapshot['name'] ?? null)) {
            return static::build($booking);
        }

        return [
            'name' => $snapshot['name'],
            'description' => $snapshot['description'] ?? null,
            'inclusions' => array_values($snapshot['inclusions'] ?? []),
            'avg_hours' => $snapshot['avg_hours'] ?? null,
            'unit_label' => $snapshot['unit_label'] ?? null,
            'addons' => array_values($snapshot['addons'] ?? []),
        ];
    }

    /**
     * Freeze the scope onto the booking if it has not been frozen already.
     * Called when a quote is sent and when an invoice is raised, so a booking
     * that skipped the quote step still gets its details captured.
     */
    public static function ensureStored(Booking $booking): void
    {
        if (filled($booking->service_snapshot['name'] ?? null)) {
            return;
        }

        $booking->update(['service_snapshot' => static::build($booking)]);
    }

    /**
     * The job-specific details that sit alongside the scope on a document —
     * what was booked for this particular property.
     *
     * @return array<int, array{label: string, value: string}>
     */
    /**
     * What the client agreed to about before & after photos, worded the same on the quote page, the PDFs and emails.
     */
    public static function photoConsentLabel(Booking $booking): string
    {
        return $booking->photo_consent
            ? 'Yes, for our records and to show online (nothing that identifies you)'
            : 'No, we won\'t take any';
    }

    public static function jobDetails(Booking $booking): array
    {
        $floorTypes = collect($booking->floor_types ?? [])->filter();

        $rows = [
            ['label' => 'Property', 'value' => $booking->property_type ?: '—'],
            ['label' => 'Bedrooms', 'value' => $booking->bedrooms ?: '—'],
            ['label' => 'Bathrooms', 'value' => $booking->bathrooms ?: '—'],
            ['label' => 'Extra Rooms', 'value' => $booking->extra_rooms ?: '—'],
            ['label' => 'Flooring', 'value' => $floorTypes->isNotEmpty() ? $floorTypes->join(', ') : 'N/A'],
            ['label' => 'Last Cleaned', 'value' => $booking->last_cleaned ?: 'N/A'],
            ['label' => 'Pets on site', 'value' => $booking->pets ? 'Yes' : 'No'],
            ['label' => 'Before & after photos', 'value' => static::photoConsentLabel($booking)],
        ];

        $hours = static::for($booking)['avg_hours'] ?? null;

        if ($hours) {
            $rows[] = ['label' => 'Estimated Time', 'value' => $hours.' hrs on site'];
        }

        if (filled($booking->access_instructions)) {
            $rows[] = ['label' => 'Access', 'value' => $booking->access_instructions];
        }

        if (filled($booking->parking)) {
            $rows[] = ['label' => 'Parking', 'value' => $booking->parking];
        }

        return $rows;
    }
}
