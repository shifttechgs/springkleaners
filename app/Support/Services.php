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
            ],
        ])->all();
    }

    public static function find(string $slug): ?array
    {
        return static::list()[$slug] ?? null;
    }

    public static function addons(): array
    {
        return ServiceAddon::orderBy('sort_order')->get()->map(fn (ServiceAddon $addon) => [
            'key' => $addon->key,
            'label' => $addon->label,
            'price' => (float) $addon->price,
            'desc' => $addon->description,
        ])->values()->all();
    }

    public static function slugs(): array
    {
        return Service::orderBy('sort_order')->pluck('slug')->all();
    }
}
