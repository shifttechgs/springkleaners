<?php

namespace App\Http\Controllers;

use App\Support\Services;
use Illuminate\View\View;

class LocationController extends Controller
{
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
