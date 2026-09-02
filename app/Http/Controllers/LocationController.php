<?php

namespace App\Http\Controllers;

use App\Support\Services;
use Illuminate\View\View;

class LocationController extends Controller
{
    private const ATLANTIC_SEABOARD = ['sea-point', 'green-point'];

    public function index(): View
    {
        $locations = collect(config('locations'));

        $clusters = collect([
            'Northern Suburbs & Blaauwberg' => $locations->except(self::ATLANTIC_SEABOARD),
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

        $otherLocations = collect(config('locations'))
            ->except($slug)
            ->take(6);

        return view('areas.show', [
            'slug' => $slug,
            'location' => $location,
            'services' => $services,
            'otherLocations' => $otherLocations,
        ]);
    }
}
