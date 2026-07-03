<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Mail\BookingConfirmationMail;
use App\Mail\InvoiceMail;
use App\Mail\ThankYouMail;
use App\Models\Booking;
use App\Models\Client;
use App\Support\QuoteExpiry;
use App\Support\Services;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View
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
            ->paginate(20)
            ->withQueryString();

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
            'timeSlots' => ['8:00 AM', '9:00 AM', '10:00 AM', '11:00 AM', '12:00 PM', '1:00 PM', '2:00 PM', '3:00 PM'],
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
            'time' => 'required|string|max:20',
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
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        $date = Carbon::parse($data['date']);

        if (! in_array($date->dayOfWeekIso, [6, 7], true)) {
            return back()->withErrors(['date' => 'SpringKleaners only services Saturdays and Sundays — pick a weekend date.'])->withInput();
        }

        $existing = Booking::whereIn('status', BookingStatus::occupyingSlot())->whereDate('date', $date->toDateString())->count();

        if ($existing >= Booking::DAILY_CAPACITY) {
            return back()->withErrors(['date' => 'That date is already at capacity ('.Booking::DAILY_CAPACITY.' bookings) — pick another weekend date.'])->withInput();
        }

        $timeTaken = Booking::whereIn('status', BookingStatus::occupyingSlot())->whereDate('date', $date->toDateString())->where('time', $data['time'])->exists();

        if ($timeTaken) {
            return back()->withErrors(['time' => 'That time slot is already booked on this date — pick another time.'])->withInput();
        }

        $client = Client::findOrCreateByPhone([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'suburb' => $data['suburb'] ?? null,
        ]);

        $booking = Booking::create([
            ...$data,
            'client_id' => $client->id,
            'booking_type' => $data['booking_type'] ?? 'once-off',
            'status' => BookingStatus::Pending,
        ]);

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
        ]);

        return back()->with('status', 'Quote generated — send it to the client below.');
    }

    public function markDepositPaid(Booking $booking): RedirectResponse
    {
        $booking->update(['deposit_paid_at' => now()]);

        return back()->with('status', 'Deposit marked as received.');
    }

    public function markPaid(Request $request, Booking $booking): RedirectResponse
    {
        $data = $request->validate([
            'payment_method' => ['required', Rule::in(['cash', 'eft'])],
        ]);

        $booking->update([
            'payment_status' => PaymentStatus::Paid,
            'payment_method' => $data['payment_method'],
            'paid_at' => now(),
        ]);

        return back()->with('status', 'Marked as paid.');
    }

    public function sendInvoiceEmail(Booking $booking): RedirectResponse
    {
        $booking->loadMissing('client');

        if (blank($booking->client?->email)) {
            return back()->withErrors(['email' => 'This client has no email on file.']);
        }

        Mail::to($booking->client->email)->send(new InvoiceMail($booking));

        return back()->with('status', 'Invoice emailed to '.$booking->client->email.'.');
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
