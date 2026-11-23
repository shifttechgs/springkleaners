@extends('layouts.app')
@section('no_app_shell', '1')

@section('title', 'Your Invoice — SpringKleaners')
@section('description', 'View and download your SpringKleaners invoice.')
@section('robots', 'noindex, nofollow')

@section('content')
    @include('components.navbar')

    <section class="bg-[#081d3a] pt-36 pb-14 lg:pt-44 lg:pb-16 relative overflow-hidden">
        <div class="section-wrap relative z-10 max-w-2xl mx-auto text-center">
            <p class="text-white/60 text-[11px] font-semibold uppercase tracking-[0.22em] mb-4">Your Invoice</p>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white leading-tight tracking-tight mb-3">
                Hi {{ $booking->name }}, here's your invoice
            </h1>
            <p class="text-white/60 text-[15px]">
                {{ $booking->invoice_number }} &middot; {{ $booking->date->format('l, d F Y') }}
                @if ($booking->invoice_revised_at)
                    <br><span class="text-[#f6e304]/90 text-[13px] font-semibold">Revised {{ $booking->invoice_revised_at->format('d M Y') }} — this is the latest version.</span>
                @endif
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

            {{-- Paying is the point of this page: amount + banking details first, the itemised breakdown after. --}}
            @if ($isPaid)
                <div class="bg-white rounded-2xl border border-emerald-100 p-6 sm:p-8 text-center">
                    <div class="w-12 h-12 rounded-full bg-emerald-50 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <h2 class="text-[#081d3a] font-bold text-[18px] mb-2">Paid in full</h2>
                    <p class="text-[#5b6779] text-[14px]">Received {{ $booking->paid_at->format('d M Y') }}. Thank you for choosing {{ \App\Support\Company::name() }}!</p>
                </div>
            @else
                @include('partials.banking-details', ['spacing' => '', 'intro' => 'Balance due: R'.number_format($balanceDue, 2).'. Pay by EFT into the account below, or pay cash on the day.'])
            @endif

            <div class="mt-6 bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
                @include('partials.quote-breakdown', ['booking' => $booking])

                <div class="pt-4 mt-4 border-t border-gray-100 space-y-1.5 text-[13px]">
                    @if ($depositPaid > 0)
                        <div class="flex justify-between text-[#5b6779]">
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
                @include('partials.banking-details', ['intro' => 'For your records.', 'class' => 'opacity-90'])
            @endif

        </div>
    </section>

    @include('components.footer')
@endsection
