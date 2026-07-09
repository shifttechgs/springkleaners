<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Mail\InvoiceMail;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function show(Booking $booking): View
    {
        abort_if(blank($booking->invoice_number), 404);

        $booking->loadMissing('client');

        return view('admin.invoices.show', ['booking' => $booking]);
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

    public function sendEmail(Booking $booking): RedirectResponse
    {
        $booking->loadMissing('client');

        if (blank($booking->client?->email)) {
            return back()->withErrors(['email' => 'This client has no email on file.']);
        }

        Mail::to($booking->client->email)->send(new InvoiceMail($booking));

        return back()->with('status', 'Invoice emailed to '.$booking->client->email.'.');
    }

    public function index(Request $request): View
    {
        $filtered = fn () => Booking::query()
            ->whereNotNull('invoice_number')
            ->when($request->filled('payment_status'), fn ($q) => $q->where('payment_status', $request->query('payment_status')))
            ->when($request->filled('from'), fn ($q) => $q->whereDate('invoiced_at', '>=', $request->query('from')))
            ->when($request->filled('to'), fn ($q) => $q->whereDate('invoiced_at', '<=', $request->query('to')))
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->query('search');
                $q->where(fn ($q) => $q->where('name', 'like', "%{$search}%")->orWhere('invoice_number', 'like', "%{$search}%"));
            });

        $invoices = $filtered()
            ->orderByDesc('invoiced_at')
            ->paginate(20)
            ->withQueryString();

        $outstanding = $filtered()
            ->where('payment_status', PaymentStatus::Unpaid)
            ->get()
            ->sum(fn (Booking $b) => (float) ($b->quoted_price ?? $b->total ?? 0) - ($b->deposit_paid_at ? (float) ($b->deposit_amount ?? 0) : 0));

        return view('admin.invoices.index', [
            'invoices' => $invoices,
            'outstanding' => $outstanding,
            'paymentStatuses' => PaymentStatus::cases(),
        ]);
    }
}
