<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Mail\BookingRequestReceivedMail;
use App\Mail\NewBookingAlertMail;
use App\Models\Booking;
use App\Models\Client;
use App\Models\User;
use App\Support\QuoteExpiry;
use App\Support\Services;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

class BookingController extends Controller
{
    public function show(Request $request): View
    {
        $services = Services::bookable();

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
        $times = $bookings->map(fn ($group) => $group->pluck('time')->map(fn ($time) => Booking::slotFor($time))->unique()->values());

        return response()->json([
            'counts' => $counts,
            'times' => $times,
            'capacity' => Booking::DAILY_CAPACITY,
            'slots' => Booking::TIME_SLOTS,
            'bookable_days' => Booking::BOOKABLE_DAYS,
            'closed' => (object) Booking::closedDates(),
            'next_available' => $this->nextAvailableDate(),
        ]);
    }

    public function reserve(Request $request): JsonResponse
    {
        QuoteExpiry::sweep();

        $data = $request->validate([
            'service' => 'required|string',
            'date' => 'required|date',
            'time' => ['required', 'string', Rule::in(Booking::TIME_SLOTS)],
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'required|email|max:255',
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
            'photo_consent' => 'nullable|boolean',
            'addons' => 'nullable|array',
            'addons.*' => 'string|max:50',
            'addon_quantities' => 'nullable|array',
            'addon_quantities.*' => 'nullable|integer|min:0|max:200',
            'booking_type' => 'nullable|string|max:20',
            'frequency' => 'nullable|string|max:50',
            'subtotal' => 'nullable|numeric',
            'total' => 'nullable|numeric',
        ]);

        $data = [...$data, ...Services::normaliseAddonSelection($data['addons'] ?? [], $data['addon_quantities'] ?? [])];
        // Before & after photos only with an explicit tick; anything else (missing, null, false) means no.
        $data['photo_consent'] = (bool) ($data['photo_consent'] ?? false);

        $date = Carbon::parse($data['date']);

        if (Booking::isClosed($date)) {
            return response()->json(['status' => 'closed', 'message' => Booking::unbookableDayMessage($date)], 422);
        }
        if ($date->startOfDay()->lt(now()->startOfDay()) || ! Booking::isBookableDay($date)) {
            return response()->json(['status' => 'invalid_day'], 422);
        }

        // Checked and saved under a per-date lock, so two people submitting at the same moment can't both get a slot.
        try {
            $result = Booking::withDateLock($date, function () use ($date, $data) {
                $existing = Booking::whereIn('status', BookingStatus::occupyingSlot())->whereDate('date', $date->toDateString())->count();
                $taken = Booking::takenSlots($date);

                if ($existing >= Booking::DAILY_CAPACITY || count($taken) >= count(Booking::TIME_SLOTS)) {
                    return 'full';
                }

                if (in_array($data['time'], $taken, true)) {
                    return 'time_taken';
                }

                $client = Client::findOrCreateByPhone([
                    'name' => $data['name'],
                    'phone' => $data['phone'],
                    'email' => $data['email'],
                    'suburb' => $data['suburb'] ?? null,
                ]);

                return Booking::create([...$data, 'client_id' => $client->id, 'status' => BookingStatus::Pending]);
            });
        } catch (LockTimeoutException) {
            $result = 'time_taken';
        }

        if ($result === 'full') {
            return response()->json([
                'status' => 'full',
                'next_available' => $this->nextAvailableDate(),
            ], 409);
        }

        if ($result === 'time_taken') {
            return response()->json(['status' => 'time_taken'], 409);
        }

        $booking = $result;

        $this->notifySubscribers($booking);
        $this->sendClientAcknowledgement($data['email'], $booking);

        return response()->json(['status' => 'ok']);
    }

    private const OFFICIAL_NOTIFY_EMAIL = 'bookings@springkleaners.co.za';

    /** Always copied on every new booking alert, on top of the official address. */
    private const COPY_NOTIFY_EMAILS = [
        'shifttechgs1@gmail.com',
        'netsaicharity@gmail.com',
    ];

    private function notifySubscribers(Booking $booking): void
    {
        $this->sendBookingAlert(self::OFFICIAL_NOTIFY_EMAIL, $booking, critical: true);

        // Sent one by one so a problem with one address never stops the others.
        foreach (self::COPY_NOTIFY_EMAILS as $email) {
            $this->sendBookingAlert($email, $booking, critical: false);
        }

        $subscribers = User::where('notify_new_bookings', true)
            ->whereNotNull('password')
            ->whereNotIn('email', [self::OFFICIAL_NOTIFY_EMAIL, ...self::COPY_NOTIFY_EMAILS])
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

    private function sendClientAcknowledgement(string $email, Booking $booking): void
    {
        try {
            Mail::to($email)->send(new BookingRequestReceivedMail($booking));
        } catch (Throwable $e) {
            Log::error('Failed to send booking request acknowledgement email to client', [
                'email' => $email,
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function nextAvailableDate(): ?string
    {
        $date = now()->startOfDay();

        for ($i = 0; $i < 120; $i++) {
            if (! Booking::isBookableDay($date)) {
                $date->addDay();

                continue;
            }

            $count = Booking::whereIn('status', BookingStatus::occupyingSlot())->whereDate('date', $date->toDateString())->count();
            if ($count < Booking::DAILY_CAPACITY && count(Booking::takenSlots($date)) < count(Booking::TIME_SLOTS)) {
                return $date->toDateString();
            }
            $date->addDay();
        }

        return null;
    }
}
