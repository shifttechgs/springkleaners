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
    public function index(): View
    {
        return view('admin.services.index', [
            'services' => Service::orderBy('sort_order')->get(),
            'addons' => ServiceAddon::orderBy('sort_order')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.services.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Service::create($this->validated($request));

        return redirect()->route('admin.services.index')->with('status', 'Service added.');
    }

    public function edit(Service $service): View
    {
        return view('admin.services.edit', ['service' => $service]);
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $service->update($this->validated($request, $service));

        return redirect()->route('admin.services.index')->with('status', 'Service updated.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();

        return back()->with('status', 'Service removed.');
    }

    private function validated(Request $request, ?Service $service = null): array
    {
        return $request->validate([
            'slug' => 'required|alpha_dash|max:100|unique:services,slug'.($service ? ','.$service->id : ''),
            'name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:500',
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
    }
}
