<?php

namespace App\Http\Controllers;

use App\Support\Services;
use Illuminate\View\View;

class LocationController extends Controller
{
    private const ATLANTIC_SEABOARD = ['sea-point', 'green-point'];

    private const NORTHERN_SUBURBS = ['durbanville', 'bellville', 'brackenfell', 'parow', 'goodwood', 'plattekloof', 'monte-vista', 'edgemead', 'bothasig'];

    public function index(): View
    {
        $locations = collect(config('locations'));

        $clusters = collect([
            'Northern Suburbs' => $locations->only(self::NORTHERN_SUBURBS),
            'Milnerton & Blaauwberg' => $locations->except([...self::ATLANTIC_SEABOARD, ...self::NORTHERN_SUBURBS]),
            'Atlantic Seaboard' => $locations->only(self::ATLANTIC_SEABOARD),
        ]);

        return view('areas.index', [
            'clusters' => $clusters,
            'totalAreas' => $locations->count(),
            'services' => Services::list(),
        ]);
    }

    public function show(string $slug): View
    {
        $location = config('locations.'.$slug);

        abort_if(! $location, 404);

        $services = Services::list();

        // Genuinely neighbouring suburbs first (config 'nearby'), so the links help someone on the edge of
        // an area. Falls back to the first six other areas for any suburb without a nearby list.
        $all = collect(config('locations'))->except($slug);
        $nearby = collect($location['nearby'] ?? [])
            ->filter(fn ($s) => $all->has($s))
            ->mapWithKeys(fn ($s) => [$s => $all[$s]]);

        $otherLocations = $nearby->isNotEmpty() ? $nearby : $all->take(6);

        return view('areas.show', [
            'slug' => $slug,
            'location' => $location,
            'services' => $services,
            'otherLocations' => $otherLocations,
        ]);
    }
}
