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

    private const OFFICIAL_NOTIFY_EMAIL = 'bookings@springkleaners.co.za';

    private function notifySubscribers(Booking $booking): void
    {
        $this->sendBookingAlert(self::OFFICIAL_NOTIFY_EMAIL, $booking, critical: true);

        $subscribers = User::where('notify_new_bookings', true)
            ->whereNotNull('password')
            ->where('email', '!=', self::OFFICIAL_NOTIFY_EMAIL)
            ->get();

        foreach ($subscribers as $user) {
            $this->sendBookingAlert($user->email, $booking, critical: false, userId: $user->id);
        }
    }

    private function sendBookingAlert(string $email, Booking $booking, bool $critical, ?int $userId = null): void
    {
        try {
            Mail::to($email)->send(new NewBookingAlertMail($booking));
        } catch (Throwable $e) {
            $context = ['email' => $email, 'booking_id' => $booking->id, 'error' => $e->getMessage()];

            if ($userId !== null) {
                $context['user_id'] = $userId;
            }

            if ($critical) {
                Log::critical('Failed to send new booking alert to the official notifications address — nobody may know about this booking yet.', $context);
            } else {
                Log::error('Failed to send new booking alert email to an optional subscriber', $context);
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
