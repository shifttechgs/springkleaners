<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceAddon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public const PER_PAGE = 10;

    public function index(Request $request): View|RedirectResponse
    {
        // Two lists on one screen, so each pages on its own query key and keeps the other's page in the links.
        $services = Service::orderBy('sort_order')->orderBy('id')->paginate(self::PER_PAGE, ['*'], 'services_page')->withQueryString();
        $addons = ServiceAddon::orderBy('sort_order')->orderBy('id')->paginate(self::PER_PAGE, ['*'], 'addons_page')->withQueryString()->fragment('addons');

        // Past the last page (e.g. after deleting its only item): go to the last page that has rows.
        $fixes = [];
        if ($services->isEmpty() && $services->currentPage() > 1) {
            $fixes['services_page'] = max(1, $services->lastPage());
        }
        if ($addons->isEmpty() && $addons->currentPage() > 1) {
            $fixes['addons_page'] = max(1, $addons->lastPage());
        }
        if ($fixes) {
            return redirect()->route('admin.services.index', [...$request->query(), ...$fixes]);
        }

        return view('admin.services.index', ['services' => $services, 'addons' => $addons]);
    }

    public function create(): View
    {
        return view('admin.services.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Service::create([...$this->validated($request), 'bookable' => $request->boolean('bookable')]);

        return redirect()->route('admin.services.index')->with('status', 'Service added.');
    }

    public function edit(Service $service): View
    {
        return view('admin.services.edit', ['service' => $service]);
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $service->update([...$this->validated($request, $service), 'bookable' => $request->boolean('bookable')]);

        return redirect()->route('admin.services.index')->with('status', 'Service updated.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();

        return back()->with('status', 'Service removed.');
    }

    private function validated(Request $request, ?Service $service = null): array
    {
        $data = $request->validate([
            'slug' => 'required|alpha_dash|max:100|unique:services,slug'.($service ? ','.$service->id : ''),
            'name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:500',
            'description' => 'nullable|string|max:2000',
            'inclusions' => 'nullable|string|max:3000',
            'icon' => 'nullable|string|max:50',
            'base_price' => 'required|numeric|min:0',
            'included_bedrooms' => 'required|integer|min:0',
            'included_bathrooms' => 'required|integer|min:0',
            'bedroom_price' => 'required|numeric|min:0',
            'bathroom_price' => 'required|numeric|min:0',
            'extra_room_price' => 'required|numeric|min:0',
            'service_fee' => 'required|numeric|min:0',
            'avg_hours' => 'required|integer|min:0',
            'unit_label' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // The form takes one inclusion per line; store it as a clean list.
        $data['inclusions'] = collect(preg_split('/\r\n|\r|\n/', $data['inclusions'] ?? ''))
            ->map(fn (string $line) => trim(ltrim($line, "-•* \t")))
            ->filter()
            ->values()
            ->all();

        return $data;
    }
}
