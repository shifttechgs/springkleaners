<?php

namespace App\Support;

use App\Models\Booking;

/**
 * Data for the instant quote + booking flow (the `quoteFlow` Alpine component in
 * resources/views/partials/quote-flow-script.blade.php). One source for every layout that books: the homepage hero
 * card and the mobile app booking sheet. Prices use the same formula as the full booking form.
 */
class QuoteFlow
{
    /** Step layouts: the hero card asks where & what together; the app sheet goes one tap-first question at a time. */
    public const LAYOUTS = [
        'hero' => [
            'steps' => ['clean', 'size', 'when', 'details'],
            'names' => ['Your clean', 'Size & price', 'Date & time', 'Your details'],
        ],
        'app' => [
            'steps' => ['service', 'size', 'where', 'when', 'details'],
            'names' => ['Service', 'Your place', 'Your suburb', 'Date & time', 'Your details'],
        ],
    ];

    /** Short, plain names for the tap-first service tiles (the full name shows once chosen). */
    public const SHORT_NAMES = [
        'deep-cleaning' => 'Deep clean',
        'end-of-tenancy' => 'Moving out',
        'move-in-cleaning' => 'Moving in',
        'post-construction' => 'After building',
        'airbnb-turnover-cleaning' => 'Airbnb turnover',
        'recurring-house-cleaning' => 'Weekly clean',
    ];

    /**
     * Bookable services with their pricing. Empty if the database is unreachable, so pages still render and the
     * card falls back to a link to the full form.
     */
    public static function services(): array
    {
        try {
            $out = [];
            foreach (Services::bookable() as $slug => $svc) {
                $out[$slug] = [
                    'name' => $svc['name'],
                    'short' => static::SHORT_NAMES[$slug] ?? $svc['name'],
                    'base_price' => (float) $svc['base_price'],
                    'included_bedrooms' => (int) $svc['included_bedrooms'],
                    'included_bathrooms' => (int) $svc['included_bathrooms'],
                    'bedroom_price' => (float) $svc['bedroom_price'],
                    'bathroom_price' => (float) $svc['bathroom_price'],
                    'extra_room_price' => (float) $svc['extra_room_price'],
                    'service_fee' => (float) $svc['service_fee'],
                    // Same photo and description as the service's own page (config/service_pages.php).
                    'img' => config("service_pages.{$slug}.hero_image") ?: "/images/works/service-pages/{$slug}.avif",
                    'alt' => config("service_pages.{$slug}.hero_alt") ?: $svc['name'],
                ];
            }

            return $out;
        } catch (\Throwable) {
            return [];
        }
    }

    public static function config(string $layout = 'hero'): array
    {
        $services = static::services();
        $steps = static::LAYOUTS[$layout] ?? static::LAYOUTS['hero'];

        return [
            'location' => $layout,
            'steps' => $steps['steps'],
            'stepNames' => $steps['names'],
            'services' => $services,
            // The hero card starts on Deep Cleaning; the app asks first, so nothing is preselected there.
            'defaultService' => $layout === 'app' ? '' : (array_key_exists('deep-cleaning', $services) ? 'deep-cleaning' : (array_key_first($services) ?? '')),
            'suburbs' => config('served_suburbs'),
            'popularSuburbs' => ['Milnerton', 'Blouberg', 'Table View', 'Century City', 'Sandown', 'Parklands'],
            'slotLabels' => Booking::TIME_SLOTS,
            'bookableDays' => Booking::BOOKABLE_DAYS,
            'availabilityUrl' => route('booking.availability'),
            'reserveUrl' => route('booking.reserve'),
            'fullFormUrl' => route('booking.show'),
        ];
    }
}
