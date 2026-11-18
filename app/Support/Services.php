<?php

namespace App\Support;

use App\Models\Service;
use App\Models\ServiceAddon;

class Services
{
    public static function list(): array
    {
        return Service::orderBy('sort_order')->get()->mapWithKeys(fn (Service $service) => [
            $service->slug => [
                'slug' => $service->slug,
                'name' => $service->name,
                'tagline' => $service->tagline,
                'description' => $service->description,
                'inclusions' => $service->inclusions ?? [],
                'icon' => $service->icon,
                'base_price' => (float) $service->base_price,
                'included_bedrooms' => $service->included_bedrooms,
                'included_bathrooms' => $service->included_bathrooms,
                'bedroom_price' => (float) $service->bedroom_price,
                'bathroom_price' => (float) $service->bathroom_price,
                'extra_room_price' => (float) $service->extra_room_price,
                'service_fee' => (float) $service->service_fee,
                'avg_hours' => $service->avg_hours,
                'unit_label' => $service->unit_label,
                'bookable' => $service->bookable,
            ],
        ])->all();
    }

    public static function find(string $slug): ?array
    {
        return static::list()[$slug] ?? null;
    }

    public static function bookable(): array
    {
        return array_filter(static::list(), fn ($service) => $service['bookable']);
    }

    public static function addons(): array
    {
        return ServiceAddon::orderBy('sort_order')->get()->map(fn (ServiceAddon $addon) => [
            'key' => $addon->key,
            'label' => $addon->label,
            'price' => (float) $addon->price,
            'unit' => $addon->unit_label,
            'desc' => $addon->description,
        ])->values()->all();
    }

    /**
     * Clean up the add-ons sent with a booking: unknown keys are dropped, per-item add-ons (windows, door
     * panels) need a quantity of at least 1 to count, and flat add-ons never carry a quantity.
     *
     * @return array{addons: array<int, string>, addon_quantities: array<string, int>|null}
     */
    public static function normaliseAddonSelection(array $keys, array $quantities): array
    {
        $byKey = collect(static::addons())->keyBy('key');
        $selected = [];
        $counts = [];

        foreach (array_unique($keys) as $key) {
            $addon = $byKey->get($key);

            if (! $addon) {
                continue;
            }

            if (filled($addon['unit'])) {
                $qty = (int) ($quantities[$key] ?? 0);

                if ($qty < 1) {
                    continue;
                }

                $counts[$key] = $qty;
            }

            $selected[] = $key;
        }

        return ['addons' => $selected, 'addon_quantities' => $counts ?: null];
    }

    public static function slugs(): array
    {
        return Service::orderBy('sort_order')->pluck('slug')->all();
    }
}
