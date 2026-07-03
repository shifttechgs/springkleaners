<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Mail\NewBookingAlertMail;
use App\Models\Booking;
use App\Models\Client;
use App\Models\User;
use App\Support\QuoteExpiry;
use App\Support\Services;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Throwable;

class BookingController extends Controller
{
    public function show(Request $request): View
    {
        $services = Services::list();

        $selected = $request->query('service');

        if (! isset($services[$selected])) {
            $selected = array_key_first($services);
        }

        return view('booking.show', [
            'services' => $services,
            'selectedSlug' => $selected,
            'addons' => Services::addons(),
        ]);
    }

    public function availability(Request $request): JsonResponse
    {
        QuoteExpiry::sweep();

        $month = Carbon::parse($request->query('month', now()->toDateString()))->startOfMonth();

        $bookings = Booking::query()
            ->whereIn('status', BookingStatus::occupyingSlot())
            ->whereBetween('date', [$month->copy()->startOfMonth()->toDateString(), $month->copy()->endOfMonth()->toDateString()])
            ->get()
            ->groupBy(fn (Booking $booking) => $booking->date->toDateString());

        $counts = $bookings->map->count();
        $times = $bookings->map(fn ($group) => $group->pluck('time')->values());

        return response()->json([
            'counts' => $counts,
            'times' => $times,
            'capacity' => Booking::DAILY_CAPACITY,
            'next_available' => $this->nextAvailableDate(),
        ]);
    }

    public function reserve(Request $request): JsonResponse
    {
        QuoteExpiry::sweep();

        $data = $request->validate([
            'service' => 'required|string',
            'date' => 'required|date',
            'time' => 'required|string',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'address' => 'nullable|string|max:255',
            'suburb' => 'nullable|string|max:255',
            'property_type' => 'nullable|string|max:50',
            'bedrooms' => 'nullable|string|max:10',
            'bathrooms' => 'nullable|string|max:10',
            'extra_rooms' => 'nullable|string|max:10',
            'last_cleaned' => 'nullable|string|max:50',
            'floor_types' => 'nullable|array',
            'floor_types.*' => 'string|max:50',
            'pets' => 'nullable|boolean',
            'notes' => 'nullable|string|max:2000',
            'access_instructions' => 'nullable|string|max:1000',
            'parking' => 'nullable|string|max:255',
            'addons' => 'nullable|array',
            'addons.*' => 'string|max:50',
            'booking_type' => 'nullable|string|max:20',
            'frequency' => 'nullable|string|max:50',
            'subtotal' => 'nullable|numeric',
            'total' => 'nullable|numeric',
        ]);

        $date = Carbon::parse($data['date']);

        if (! in_array($date->dayOfWeekIso, [6, 7], true)) {
            return response()->json(['status' => 'invalid_day'], 422);
        }

        if ($date->startOfDay()->lt(now()->startOfDay())) {
            return response()->json(['status' => 'invalid_day'], 422);
        }

        $existing = Booking::whereIn('status', BookingStatus::occupyingSlot())->whereDate('date', $date->toDateString())->count();

        if ($existing >= Booking::DAILY_CAPACITY) {
            return response()->json([
                'status' => 'full',
                'next_available' => $this->nextAvailableDate(),
            ], 409);
        }

        $timeTaken = Booking::whereIn('status', BookingStatus::occupyingSlot())->whereDate('date', $date->toDateString())->where('time', $data['time'])->exists();

        if ($timeTaken) {
            return response()->json(['status' => 'time_taken'], 409);
        }

        $client = Client::findOrCreateByPhone([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'suburb' => $data['suburb'] ?? null,
        ]);

        $booking = Booking::create([...$data, 'client_id' => $client->id, 'status' => BookingStatus::Pending]);

        $this->notifySubscribers($booking);

        return response()->json(['status' => 'ok']);
    }

    private function notifySubscribers(Booking $booking): void
    {
        $subscribers = User::where('notify_new_bookings', true)->whereNotNull('password')->get();

        foreach ($subscribers as $user) {
            try {
                Mail::to($user->email)->send(new NewBookingAlertMail($booking));
            } catch (Throwable $e) {
                Log::error('Failed to send new booking alert email', ['user_id' => $user->id, 'booking_id' => $booking->id, 'error' => $e->getMessage()]);
            }
        }
    }

    private function nextAvailableDate(): ?string
    {
        $date = now()->startOfDay();

        for ($i = 0; $i < 120; $i++) {
            if (in_array($date->dayOfWeekIso, [6, 7], true)) {
                $count = Booking::whereIn('status', BookingStatus::occupyingSlot())->whereDate('date', $date->toDateString())->count();
                if ($count < Booking::DAILY_CAPACITY) {
                    return $date->toDateString();
                }
            }
            $date->addDay();
        }

        return null;
    }
}
