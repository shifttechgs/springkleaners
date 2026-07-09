@extends('admin.layout')
@section('title', $booking->invoice_number.' — Invoice')
@section('content')

    @php
        $waNumber = preg_replace('/\D+/', '', $booking->phone);
        if (str_starts_with($waNumber, '0')) { $waNumber = '27'.substr($waNumber, 1); }
    @endphp

    <div class="flex items-center gap-2 text-label text-[12px] font-medium mb-6">
        <a href="{{ route('admin.invoices.index') }}" class="hover:text-navy transition-colors">Invoices</a>
        <span>/</span>
        <span class="text-ink">{{ $booking->invoice_number }}</span>
    </div>

    @if (session('status'))
        <div class="mb-6 px-4 py-3 bg-emerald-50 border border-emerald-100 rounded-xl text-emerald-700 text-[13px] font-medium">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 px-4 py-3 bg-rose-50 border border-rose-100 rounded-xl text-rose-600 text-[13px] font-medium">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="flex items-start justify-between mb-6 flex-wrap gap-4">
        <div class="flex items-center gap-4">
            <div class="avatar" style="width:44px;height:44px;border-radius:14px;font-size:16px;">{{ strtoupper(substr($booking->name, 0, 1)) }}</div>
            <div>
                <h1 class="text-[22px] font-extrabold tracking-tight leading-tight">{{ $booking->invoice_number }}</h1>
                <p class="text-muted text-[13px] mt-0.5">
                    {{ $booking->name }} &middot; {{ $booking->invoiced_at?->format('d M Y') }}
                    &middot; <a href="{{ route('admin.bookings.show', $booking) }}" class="text-navy font-semibold hover:opacity-70 transition-opacity">View Booking &rarr;</a>
                </p>
            </div>
        </div>
        <x-admin.status-badge :status="$booking->payment_status" />
    </div>

    {{-- Quick actions --}}
    <div class="flex flex-wrap items-center gap-2 mb-8 pb-6 border-b border-line" x-data="{ copied: false }">

        <a href="{{ route('admin.invoices.pdf.preview', $booking) }}" target="_blank" rel="noopener noreferrer"
           class="inline-flex items-center gap-2 bg-white border border-line text-ink font-semibold px-4 py-2.5 rounded-xl text-[13px] hover:border-navy transition-colors">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            Preview PDF
        </a>

        <a href="{{ route('admin.invoices.pdf', $booking) }}"
           class="inline-flex items-center gap-2 bg-white border border-line text-ink font-semibold px-4 py-2.5 rounded-xl text-[13px] hover:border-navy transition-colors">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
            Download PDF
        </a>

        <div class="flex-1"></div>

        @php
            $invoiceUrl = route('invoice.show', $booking->accepted_token);
            $invoiceMessage = "Hi {$booking->name}! Here's your invoice ({$booking->invoice_number}) from SpringKleaners: R".number_format((float) ($booking->quoted_price ?? $booking->total ?? 0), 2)."\n\nView & download: {$invoiceUrl}";
        @endphp
        <button type="button" @click="navigator.clipboard.writeText('{{ $invoiceUrl }}'); copied = true; setTimeout(() => copied = false, 2000)"
                class="inline-flex items-center gap-2 bg-white border border-line text-ink font-semibold px-4 py-2.5 rounded-xl text-[13px] hover:border-navy transition-colors">
            <span x-text="copied ? 'Copied!' : 'Copy Link'"></span>
        </button>
        <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode($invoiceMessage) }}" target="_blank" rel="noopener noreferrer"
           class="inline-flex items-center gap-2 bg-[#25d366] text-white font-semibold px-4 py-2.5 rounded-xl text-[13px] hover:bg-[#20bd5a] transition-colors">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347M12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0012.05 0"/></svg>
            WhatsApp
        </a>
        <form method="POST" action="{{ route('admin.invoices.send-email', $booking) }}">
            @csrf
            <button type="submit" class="inline-flex items-center gap-2 bg-white border border-line text-ink font-semibold px-4 py-2.5 rounded-xl text-[13px] hover:border-navy transition-colors">
                Email
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 space-y-6">
            <div class="card p-6">
                @include('partials.quote-breakdown', ['booking' => $booking])
            </div>
        </div>

        <div class="space-y-6">

            @if ($booking->deposit_amount > 0)
                <div class="card p-6">
                    <h2 class="font-bold text-[14px] tracking-tight mb-1">Deposit</h2>
                    <p class="text-muted text-[12.5px] mb-4">R{{ number_format((float) $booking->deposit_amount, 2) }} required.</p>
                    @if ($booking->deposit_paid_at)
                        <p class="text-emerald-600 text-[13px] font-semibold">Received {{ $booking->deposit_paid_at->format('d M Y') }}.</p>
                    @else
                        <form method="POST" action="{{ route('admin.invoices.mark-deposit-paid', $booking) }}">
                            @csrf
                            <button type="submit" class="w-full justify-center inline-flex items-center gap-2 bg-white border border-line text-ink font-semibold px-4 py-2.5 rounded-xl text-[13px] hover:border-navy transition-colors">
                                Mark Deposit as Received
                            </button>
                        </form>
                    @endif
                </div>
            @endif

            <div class="card p-6">
                <h2 class="font-bold text-[14px] tracking-tight mb-1">Payment</h2>
                <p class="text-muted text-[12.5px] mb-4">Total: R{{ number_format((float) ($booking->quoted_price ?? $booking->total ?? 0), 2) }}</p>

                @if ($booking->payment_status->value === 'paid')
                    <p class="text-emerald-600 text-[13px] font-semibold">
                        Paid {{ $booking->paid_at->format('d M Y') }} &middot; {{ ucfirst($booking->payment_method) }}
                    </p>
                @else
                    <form method="POST" action="{{ route('admin.invoices.mark-paid', $booking) }}" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-label text-[11px] uppercase tracking-wider mb-1.5 font-semibold">Payment Method</label>
                            <select name="payment_method" required class="w-full bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
                                <option value="">Select method</option>
                                <option value="cash">Cash</option>
                                <option value="eft">EFT</option>
                            </select>
                        </div>
                        <button type="submit" class="btn-primary w-full justify-center">Mark as Paid</button>
                    </form>
                @endif
            </div>

        </div>
    </div>

@endsection
