<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceAddon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceAddonController extends Controller
{
    public function create(): View
    {
        return view('admin.service-addons.create');
    }

    public function store(Request $request): RedirectResponse
    {
        ServiceAddon::create($this->validated($request));

        return redirect()->route('admin.services.index')->with('status', 'Add-on added.');
    }

    public function edit(ServiceAddon $serviceAddon): View
    {
        return view('admin.service-addons.edit', ['addon' => $serviceAddon]);
    }

    public function update(Request $request, ServiceAddon $serviceAddon): RedirectResponse
    {
        $serviceAddon->update($this->validated($request, $serviceAddon));

        return redirect()->route('admin.services.index')->with('status', 'Add-on updated.');
    }

    public function destroy(ServiceAddon $serviceAddon): RedirectResponse
    {
        $serviceAddon->delete();

        return back()->with('status', 'Add-on removed.');
    }

    private function validated(Request $request, ?ServiceAddon $addon = null): array
    {
        return $request->validate([
            'key' => 'required|alpha_dash|max:100|unique:service_addons,key'.($addon ? ','.$addon->id : ''),
            'label' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
        ]);
    }
}
