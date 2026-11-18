<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BookingStatus;
use App\Http\Controllers\Controller;
use App\Mail\BookingConfirmationMail;
use App\Mail\ThankYouMail;
use App\Models\Booking;
use App\Models\Client;
use App\Support\AdminPagination;
use App\Support\QuoteExpiry;
use App\Support\Services;
use App\Support\ServiceSnapshot;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        QuoteExpiry::sweep();

        $bookings = Booking::query()
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->query('status')))
            ->when($request->filled('service'), fn ($q) => $q->where('service', $request->query('service')))
            ->when($request->filled('date'), fn ($q) => $q->whereDate('date', $request->query('date')))
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->query('search');
                $q->where(fn ($q) => $q->where('name', 'like', "%{$search}%")->orWhere('phone', 'like', "%{$search}%"));
            })
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->paginate(AdminPagination::PER_PAGE)
            ->withQueryString();

        if ($redirect = AdminPagination::pastEnd($request, $bookings)) {
            return $redirect;
        }

        return view('admin.bookings.index', [
            'bookings' => $bookings,
            'statuses' => BookingStatus::cases(),
            'services' => Services::list(),
        ]);
    }

    public function show(Booking $booking): View
    {
        $booking->load('client', 'expenses');

        return view('admin.bookings.show', [
            'booking' => $booking,
            'statuses' => BookingStatus::cases(),
        ]);
    }

    public function create(): View
    {
        QuoteExpiry::sweep();

        return view('admin.bookings.create', [
            'services' => Services::list(),
            'addons' => Services::addons(),
            'timeSlots' => Booking::TIME_SLOTS,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        QuoteExpiry::sweep();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'service' => 'required|string|in:'.implode(',', Services::slugs()),
            'date' => 'required|date',
            'time' => ['required', 'string', Rule::in(Booking::TIME_SLOTS)],
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
            'addon_quantities' => 'nullable|array',
            'addon_quantities.*' => 'nullable|integer|min:0|max:200',
            'booking_type' => 'nullable|string|max:20',
            'frequency' => 'nullable|string|max:50',
            'subtotal' => 'nullable|numeric',
            'total' => 'nullable|numeric',
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        $data = [...$data, ...Services::normaliseAddonSelection($data['addons'] ?? [], $data['addon_quantities'] ?? [])];

        $date = Carbon::parse($data['date']);

        if (! Booking::isBookableDay($date)) {
            return back()->withErrors(['date' => 'We only take bookings on Saturdays and Sundays — pick a weekend date.'])->withInput();
        }

        // Checked and saved under the same per-date lock as website bookings, so nothing can double-book a slot.
        try {
            $result = Booking::withDateLock($date, function () use ($date, $data) {
                $existing = Booking::whereIn('status', BookingStatus::occupyingSlot())->whereDate('date', $date->toDateString())->count();
                $taken = Booking::takenSlots($date);

                if ($existing >= Booking::DAILY_CAPACITY || count($taken) >= count(Booking::TIME_SLOTS)) {
                    return ['date' => 'That date is fully booked (both slots are taken) — pick another date.'];
                }

                if (in_array($data['time'], $taken, true)) {
                    return ['time' => 'That slot is already booked on this date — pick the other slot.'];
                }

                $client = Client::findOrCreateByPhone([
                    'name' => $data['name'],
                    'phone' => $data['phone'],
                    'suburb' => $data['suburb'] ?? null,
                ]);

                return Booking::create([
                    ...$data,
                    'client_id' => $client->id,
                    'booking_type' => $data['booking_type'] ?? 'once-off',
                    'status' => BookingStatus::Pending,
                ]);
            });
        } catch (LockTimeoutException) {
            $result = ['time' => 'Someone else is booking this date right now — try again in a moment.'];
        }

        if (is_array($result)) {
            return back()->withErrors($result)->withInput();
        }

        $booking = $result;

        return redirect()->route('admin.bookings.show', $booking)->with('status', 'Booking recorded — review and send a quote when ready.');
    }

    public function update(Request $request, Booking $booking): RedirectResponse
    {
        $data = $request->validate([
            'status' => 'required|string|in:'.implode(',', array_column(BookingStatus::cases(), 'value')),
            'quoted_price' => 'nullable|numeric|min:0',
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        if ($data['status'] === BookingStatus::Completed->value && blank($booking->invoice_number)) {
            $data['invoice_number'] = 'INV-'.now()->format('Y').'-'.str_pad((string) $booking->id, 4, '0', STR_PAD_LEFT);
            $data['invoiced_at'] = now();
            $data['accepted_token'] = $booking->accepted_token ?? (string) Str::uuid();

            // Bookings invoiced without a quote step still need their scope frozen.
            ServiceSnapshot::ensureStored($booking);
        }

        // Reopening a cancelled or expired booking takes its slot back, so it must still be free.
        $occupying = array_map(fn (BookingStatus $s) => $s->value, BookingStatus::occupyingSlot());
        $wasOccupying = in_array($booking->status?->value, $occupying, true);
        $willOccupy = in_array($data['status'], $occupying, true);

        if (! $wasOccupying && $willOccupy) {
            $slot = Booking::slotFor($booking->time);
            $clash = Booking::whereIn('status', BookingStatus::occupyingSlot())
                ->whereDate('date', $booking->date->toDateString())
                ->whereKeyNot($booking->id)
                ->pluck('time')
                ->contains(fn ($time) => Booking::slotFor($time) === $slot);

            if ($clash) {
                return back()->withErrors(['status' => "Can't reopen this booking: the {$slot} slot on {$booking->date->format('j M Y')} has been taken by another booking."]);
            }
        }

        $booking->update($data);

        return back()->with('status', 'Booking updated.');
    }

    public function sendQuote(Request $request, Booking $booking): RedirectResponse
    {
        $data = $request->validate([
            'quoted_price' => 'required|numeric|min:0',
            'deposit_amount' => 'nullable|numeric|min:0',
        ]);

        $booking->update([
            'quoted_price' => $data['quoted_price'],
            'deposit_amount' => $data['deposit_amount'] ?? null,
            'status' => BookingStatus::Quoted,
            'accepted_token' => $booking->accepted_token ?? (string) Str::uuid(),
            'quote_sent_at' => now(),
            // Freeze the scope the client is being quoted on.
            'service_snapshot' => ServiceSnapshot::build($booking),
        ]);

        return back()->with('status', 'Quote generated — send it to the client below.');
    }

    public function sendConfirmationEmail(Booking $booking): RedirectResponse
    {
        $booking->loadMissing('client');

        if (blank($booking->client?->email)) {
            return back()->withErrors(['email' => 'This client has no email on file.']);
        }

        Mail::to($booking->client->email)->send(new BookingConfirmationMail($booking));
        $booking->update(['confirmation_sent_at' => now()]);

        return back()->with('status', 'Booking confirmation emailed to '.$booking->client->email.'.');
    }

    public function sendThankYouEmail(Booking $booking): RedirectResponse
    {
        $booking->loadMissing('client');

        if (blank($booking->client?->email)) {
            return back()->withErrors(['email' => 'This client has no email on file.']);
        }

        Mail::to($booking->client->email)->send(new ThankYouMail($booking));
        $booking->update(['thank_you_sent_at' => now()]);

        return back()->with('status', 'Thank you emailed to '.$booking->client->email.'.');
    }
}
