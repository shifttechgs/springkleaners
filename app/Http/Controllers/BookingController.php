<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function show(Request $request): View
    {
        $services = config('cleaning_services.list');

        $selected = $request->query('service');

        if (! isset($services[$selected])) {
            $selected = array_key_first($services);
        }

        return view('booking.show', [
            'services' => $services,
            'selectedSlug' => $selected,
            'addons' => config('cleaning_services.addons'),
        ]);
    }

    public function availability(Request $request): JsonResponse
    {
        $month = Carbon::parse($request->query('month', now()->toDateString()))->startOfMonth();

        $counts = Booking::query()
            ->whereBetween('date', [$month->copy()->startOfMonth()->toDateString(), $month->copy()->endOfMonth()->toDateString()])
            ->get()
            ->groupBy(fn (Booking $booking) => $booking->date->toDateString())
            ->map->count();

        return response()->json([
            'counts' => $counts,
            'capacity' => Booking::DAILY_CAPACITY,
            'next_available' => $this->nextAvailableDate(),
        ]);
    }

    public function reserve(Request $request): JsonResponse
    {
        $data = $request->validate([
            'service' => 'required|string',
            'date' => 'required|date',
            'time' => 'required|string',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'address' => 'nullable|string|max:255',
            'suburb' => 'nullable|string|max:255',
        ]);

        $date = Carbon::parse($data['date']);

        if (! in_array($date->dayOfWeekIso, [6, 7], true)) {
            return response()->json(['status' => 'invalid_day'], 422);
        }

        if ($date->startOfDay()->lt(now()->startOfDay())) {
            return response()->json(['status' => 'invalid_day'], 422);
        }

        $existing = Booking::whereDate('date', $date->toDateString())->count();

        if ($existing >= Booking::DAILY_CAPACITY) {
            return response()->json([
                'status' => 'full',
                'next_available' => $this->nextAvailableDate(),
            ], 409);
        }

        Booking::create($data);

        return response()->json(['status' => 'ok']);
    }

    private function nextAvailableDate(): ?string
    {
        $date = now()->startOfDay();

        for ($i = 0; $i < 120; $i++) {
            if (in_array($date->dayOfWeekIso, [6, 7], true)) {
                $count = Booking::whereDate('date', $date->toDateString())->count();
                if ($count < Booking::DAILY_CAPACITY) {
                    return $date->toDateString();
                }
            }
            $date->addDay();
        }

        return null;
    }
}
