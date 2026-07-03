@extends('layouts.app')

@section('title', 'Your Invoice — SpringKleaners')
@section('description', 'View and download your SpringKleaners invoice.')

@section('content')
    @include('components.navbar')

    <section class="bg-[#081d3a] pt-36 pb-14 lg:pt-44 lg:pb-16 relative overflow-hidden">
        <div class="absolute inset-0 hero-pattern"></div>
        <div class="section-wrap relative z-10 max-w-2xl mx-auto text-center">
            <p class="text-white/40 text-[11px] font-semibold uppercase tracking-[0.22em] mb-4">Your Invoice</p>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white leading-tight tracking-tight mb-3">
                Hi {{ $booking->name }}, here's your invoice
            </h1>
            <p class="text-white/60 text-[15px]">
                {{ $booking->invoice_number }} &middot; {{ $booking->date->format('l, d F Y') }}
            </p>
        </div>
    </section>

    <section class="bg-light section-py !py-14">
        <div class="section-wrap max-w-2xl mx-auto">

            @php
                $subtotal = (float) ($booking->quoted_price ?? $booking->total ?? 0);
                $depositPaid = $booking->deposit_paid_at ? (float) ($booking->deposit_amount ?? 0) : 0;
                $balanceDue = max(0, $subtotal - $depositPaid);
                $isPaid = $booking->payment_status->value === 'paid';
            @endphp

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
                @include('partials.quote-breakdown', ['booking' => $booking])

                <div class="pt-4 mt-4 border-t border-gray-100 space-y-1.5 text-[13px]">
                    @if ($depositPaid > 0)
                        <div class="flex justify-between text-[#647082]">
                            <span>Less: Deposit Paid</span>
                            <span>&minus;R{{ number_format($depositPaid, 2) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-[#081d3a] font-bold text-[15px] pt-1.5">
                        <span>Balance Due</span>
                        <span>R{{ number_format($balanceDue, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex flex-col sm:flex-row gap-3">
                <a href="{{ route('invoice.download', $booking->accepted_token) }}"
                   class="flex-1 flex items-center justify-center gap-2 bg-[#f6e304] text-[#081d3a] font-bold py-4 rounded-xl hover:bg-yellow-300 active:scale-95 transition-all text-[15px]">
                    Download PDF
                </a>
            </div>

            @if ($isPaid)
                <div class="mt-8 bg-white rounded-2xl border border-emerald-100 p-6 sm:p-8 text-center">
                    <div class="w-12 h-12 rounded-full bg-emerald-50 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <h2 class="text-[#081d3a] font-bold text-[18px] mb-2">Paid in full</h2>
                    <p class="text-[#647082] text-[14px]">Received {{ $booking->paid_at->format('d M Y') }}. Thank you for choosing {{ \App\Support\Company::name() }}!</p>
                </div>
            @else
                <div class="mt-8 bg-white rounded-2xl border border-gray-100 p-6 sm:p-8">
                    <h2 class="text-[#081d3a] font-bold text-[16px] mb-3">Payment Details</h2>
                    <p class="text-[#647082] text-[14px] leading-relaxed">
                        {{ \App\Support\Company::bankName() }} &middot; Branch Code {{ \App\Support\Company::branchCode() }} &middot; Account No {{ \App\Support\Company::accountNo() }}<br>
                        {{ \App\Support\Company::referenceNote() }}<br>
                        Or pay cash on the day.
                    </p>
                </div>
            @endif

        </div>
    </section>

    @include('components.footer')
@endsection
