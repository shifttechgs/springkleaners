@extends('admin.layout')
@section('title', 'Invoices')
@section('content')

    <div class="flex items-start justify-between mb-8 flex-wrap gap-4">
        <div>
            <p class="text-label text-[11px] uppercase tracking-wider font-semibold mb-1">Money In</p>
            <h1 class="text-[26px] font-extrabold tracking-tight leading-none">Invoices</h1>
        </div>
    </div>

    @if (session('status'))
        <div class="mb-6 px-4 py-3 bg-emerald-50 border border-emerald-100 rounded-xl text-emerald-700 text-[13px] font-medium">
            {{ session('status') }}
        </div>
    @endif

    <form method="GET" class="flex flex-wrap gap-2.5 mb-6">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search client or invoice #..."
               class="bg-white border border-line rounded-xl px-4 py-2.5 text-[13px] text-ink placeholder-label focus:border-navy focus:outline-none transition-colors w-64">
        <select name="payment_status" class="bg-white border border-line rounded-xl px-4 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
            <option value="">All statuses</option>
            @foreach ($paymentStatuses as $status)
                <option value="{{ $status->value }}" @selected(request('payment_status') === $status->value)>{{ $status->label() }}</option>
            @endforeach
        </select>
        <input type="date" name="from" value="{{ request('from') }}" class="bg-white border border-line rounded-xl px-4 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
        <input type="date" name="to" value="{{ request('to') }}" class="bg-white border border-line rounded-xl px-4 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
        <button type="submit" class="btn-primary">Filter</button>
        @if (request()->anyFilled(['search', 'payment_status', 'from', 'to']))
            <a href="{{ route('admin.invoices.index') }}" class="flex items-center text-muted font-semibold text-[13px] hover:text-navy transition-colors px-1">Clear</a>
        @endif
    </form>

    <div class="card p-5 mb-6 flex items-center justify-between">
        <p class="text-label text-[11px] uppercase tracking-wider font-semibold">Outstanding for this filter</p>
        <p class="font-extrabold text-[22px] tracking-tight text-ink">R{{ number_format($outstanding, 2) }}</p>
    </div>

    <div class="card overflow-hidden">
        @if ($invoices->isEmpty())
            <div class="px-6 py-14 text-center">
                <p class="text-muted text-[13px]">No invoices match those filters.</p>
            </div>
        @else
            <table class="w-full data-table">
                <thead>
                    <tr>
                        <th class="px-6 py-3">Invoice #</th>
                        <th class="px-6 py-3">Client</th>
                        <th class="px-6 py-3">Job Date</th>
                        <th class="px-6 py-3">Amount</th>
                        <th class="px-6 py-3">Balance Due</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @foreach ($invoices as $booking)
                        @php
                            $amount = (float) ($booking->quoted_price ?? $booking->total ?? 0);
                            $depositPaid = $booking->deposit_paid_at ? (float) ($booking->deposit_amount ?? 0) : 0;
                            $balanceDue = max(0, $amount - $depositPaid);
                        @endphp
                        <tr>
                            <td class="px-6 py-3.5 font-semibold text-ink">{{ $booking->invoice_number }}</td>
                            <td class="px-6 py-3.5">{{ $booking->name }}</td>
                            <td class="px-6 py-3.5 text-muted">{{ $booking->date->format('d M Y') }}</td>
                            <td class="px-6 py-3.5 text-muted">R{{ number_format($amount, 2) }}</td>
                            <td class="px-6 py-3.5 font-semibold text-ink">R{{ number_format($balanceDue, 2) }}</td>
                            <td class="px-6 py-3.5">
                                <x-admin.status-badge :status="$booking->payment_status" />
                            </td>
                            <td class="px-6 py-3.5 text-right">
                                <a href="{{ route('admin.invoices.show', $booking) }}" class="text-navy font-semibold hover:opacity-70 transition-opacity">View &rarr;</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-6 py-4 border-t border-line">
                {{ $invoices->links() }}
            </div>
        @endif
    </div>

@endsection
