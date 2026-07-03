<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FuelSettingController extends Controller
{
    public function edit(): View
    {
        return view('admin.fuel-settings.edit', [
            'petrolPrice' => (float) Setting::get('fuel_petrol_price_per_litre', 23.50),
            'vehicleKmpl' => (float) Setting::get('fuel_vehicle_kmpl', 10),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'petrol_price_per_litre' => 'required|numeric|min:0',
            'vehicle_kmpl' => 'required|numeric|min:0.1',
        ]);

        Setting::set('fuel_petrol_price_per_litre', $data['petrol_price_per_litre']);
        Setting::set('fuel_vehicle_kmpl', $data['vehicle_kmpl']);

        return back()->with('status', 'Fuel settings updated.');
    }
}
