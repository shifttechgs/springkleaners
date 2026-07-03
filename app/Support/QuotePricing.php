<?php

namespace App\Support;

use App\Models\Booking;

class QuotePricing
{
    private const BEDROOM_COUNTS = ['1' => 1, '2' => 2, '3' => 3, '4' => 4, '5+' => 5];
    private const BATHROOM_COUNTS = ['1' => 1, '2' => 2, '3' => 3, '4+' => 4];
    private const ROOM_COUNTS = ['0' => 0, '1' => 1, '2' => 2, '3' => 3, '4+' => 4];

    /**
     * Build an itemised quote breakdown for a booking, for display on the
     * admin quote PDF. Reads the stored booking fields + static service
     * config — does not re-derive the persisted subtotal/total, only
     * explains how they were made up.
     *
     * @return array<int, array{description: string, qty: int, unit_price: float, total: float}>
     */
    public static function lineItems(Booking $booking): array
    {
        $service = Services::find($booking->service) ?? [];
        $addonsList = collect(Services::addons());

        $bedroomCount = self::BEDROOM_COUNTS[$booking->bedrooms] ?? 0;
        $bathroomCount = self::BATHROOM_COUNTS[$booking->bathrooms] ?? 0;
        $extraRoomsCount = self::ROOM_COUNTS[$booking->extra_rooms] ?? 0;

        $includedBedrooms = $service['included_bedrooms'] ?? 0;
        $includedBathrooms = $service['included_bathrooms'] ?? 0;

        $extraBedrooms = max(0, $bedroomCount - $includedBedrooms);
        $extraBathrooms = max(0, $bathroomCount - $includedBathrooms);

        $items = [
            [
                'description' => ($service['name'] ?? $booking->service).' — base rate ('.$includedBedrooms.' bed, '.$includedBathrooms.' bath included)',
                'qty' => 1,
                'unit_price' => (float) ($service['base_price'] ?? 0),
                'total' => (float) ($service['base_price'] ?? 0),
            ],
        ];

        if ($extraBedrooms > 0) {
            $items[] = [
                'description' => 'Extra bedroom(s)',
                'qty' => $extraBedrooms,
                'unit_price' => (float) ($service['bedroom_price'] ?? 0),
                'total' => $extraBedrooms * (float) ($service['bedroom_price'] ?? 0),
            ];
        }

        if ($extraBathrooms > 0) {
            $items[] = [
                'description' => 'Extra bathroom(s)',
                'qty' => $extraBathrooms,
                'unit_price' => (float) ($service['bathroom_price'] ?? 0),
                'total' => $extraBathrooms * (float) ($service['bathroom_price'] ?? 0),
            ];
        }

        if ($extraRoomsCount > 0) {
            $items[] = [
                'description' => 'Extra room(s)',
                'qty' => $extraRoomsCount,
                'unit_price' => (float) ($service['extra_room_price'] ?? 0),
                'total' => $extraRoomsCount * (float) ($service['extra_room_price'] ?? 0),
            ];
        }

        foreach (($booking->addons ?? []) as $addonKey) {
            $addon = $addonsList->firstWhere('key', $addonKey);

            if (! $addon) {
                continue;
            }

            $items[] = [
                'description' => $addon['label'],
                'qty' => 1,
                'unit_price' => (float) $addon['price'],
                'total' => (float) $addon['price'],
            ];
        }

        $items[] = [
            'description' => 'Service fee',
            'qty' => 1,
            'unit_price' => (float) ($service['service_fee'] ?? 0),
            'total' => (float) ($service['service_fee'] ?? 0),
        ];

        return $items;
    }
}
