<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Distance
{
    public static function roundTripKm(?string $address, ?string $suburb): ?float
    {
        $key = config('services.ors.key');

        if (blank($key) || blank($address)) {
            return null;
        }

        try {
            $hq = static::hqCoordinates($key);
            $destination = static::geocode($key, trim($address.', '.$suburb.', Cape Town, South Africa'));

            if (! $hq || ! $destination) {
                return null;
            }

            $oneWayKm = static::drivingDistanceKm($key, $hq, $destination);

            return $oneWayKm !== null ? round($oneWayKm * 2, 1) : null;
        } catch (\Throwable $e) {
            Log::warning('Distance::roundTripKm failed: '.$e->getMessage());

            return null;
        }
    }

    private static function hqCoordinates(string $key): ?array
    {
        return Cache::rememberForever('hq_coordinates', function () use ($key) {
            return static::geocode($key, Company::hqAddress());
        });
    }

    private static function geocode(string $key, string $query): ?array
    {
        $response = Http::timeout(12)->get('https://api.openrouteservice.org/geocode/search', [
            'api_key' => $key,
            'text' => $query,
            'boundary.country' => 'ZA',
            'size' => 1,
        ]);

        if (! $response->ok()) {
            return null;
        }

        $coordinates = $response->json('features.0.geometry.coordinates');

        return is_array($coordinates) ? $coordinates : null;
    }

    private static function drivingDistanceKm(string $key, array $origin, array $destination): ?float
    {
        $response = Http::timeout(12)
            ->withHeaders(['Authorization' => $key])
            ->post('https://api.openrouteservice.org/v2/matrix/driving-car', [
                'locations' => [$origin, $destination],
                'sources' => [0],
                'destinations' => [1],
                'metrics' => ['distance'],
            ]);

        if (! $response->ok()) {
            return null;
        }

        $meters = $response->json('distances.0.0');

        return is_numeric($meters) ? $meters / 1000 : null;
    }
}
