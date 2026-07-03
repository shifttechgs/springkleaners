<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvoiceController extends Controller
{
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
