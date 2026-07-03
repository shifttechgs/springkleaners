@extends('layouts.app')

@section('title', 'Your Quote — SpringKleaners')
@section('description', 'View and confirm your SpringKleaners cleaning quote.')

@section('content')
    @include('components.navbar')

    <section class="bg-[#081d3a] pt-36 pb-14 lg:pt-44 lg:pb-16 relative overflow-hidden">
        <div class="absolute inset-0 hero-pattern"></div>
        <div class="section-wrap relative z-10 max-w-2xl mx-auto text-center">
            <p class="text-white/40 text-[11px] font-semibold uppercase tracking-[0.22em] mb-4">Your Quote</p>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white leading-tight tracking-tight mb-3">
                Hi {{ $booking->name }}, here's your cleaning quote
            </h1>
            <p class="text-white/60 text-[15px]">
                {{ \App\Support\Services::find($booking->service)['name'] ?? $booking->service }} &middot;
                {{ $booking->date->format('l, d F Y') }} &middot; {{ $booking->time }}
            </p>
        </div>
    </section>

    <section class="bg-light section-py !py-14">
        <div class="section-wrap max-w-2xl mx-auto">

            @if (session('status'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-xl text-emerald-700 text-[14px] font-medium text-center">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
                @include('partials.quote-breakdown', ['booking' => $booking])
            </div>

            @if ($booking->invoice_number)
                <div class="mt-4 text-center">
                    <a href="{{ route('invoice.show', $booking->accepted_token) }}" class="text-[#081d3a] font-semibold text-[14px] hover:opacity-70 transition-opacity">View your invoice &rarr;</a>
                </div>
            @endif

            @if ($booking->status->value === 'quoted')
                <div class="mt-8 flex flex-col sm:flex-row gap-3">
                    <form method="POST" action="{{ route('quote.accept', $booking->accepted_token) }}" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-2 bg-[#f6e304] text-[#081d3a] font-bold py-4 rounded-xl hover:bg-yellow-300 active:scale-95 transition-all text-[15px]">
                            Accept Quote
                        </button>
                    </form>
                    <form method="POST" action="{{ route('quote.decline', $booking->accepted_token) }}" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-2 bg-white border-2 border-[#081d3a]/15 text-[#081d3a] font-bold py-4 rounded-xl hover:border-[#081d3a]/30 active:scale-95 transition-all text-[15px]">
                            Decline
                        </button>
                    </form>
                </div>
            @elseif ($booking->status->value === 'accepted')
                <div class="mt-8 bg-white rounded-2xl border border-emerald-100 p-6 sm:p-8 text-center">
                    <div class="w-12 h-12 rounded-full bg-emerald-50 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <h2 class="text-[#081d3a] font-bold text-[18px] mb-2">Quote accepted!</h2>
                    <p class="text-[#647082] text-[14px] mb-6">We've noted your confirmation. Message us on WhatsApp to lock in your booking date and finalise the details.</p>
                    <a href="https://wa.me/27815274711?text={{ urlencode('Hi SpringKleaners! I just accepted my quote for '.($booking->name).' — confirming my booking for '.$booking->date->format('d M Y').'.') }}"
                       target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 bg-[#25d366] text-white font-bold px-6 py-3.5 rounded-xl hover:bg-[#20bd5a] transition-colors text-[14px]">
                        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347M12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0012.05 0"/></svg>
                        Message us on WhatsApp
                    </a>
                </div>
            @elseif ($booking->status->value === 'declined')
                <div class="mt-8 bg-white rounded-2xl border border-gray-100 p-6 sm:p-8 text-center">
                    <h2 class="text-[#081d3a] font-bold text-[18px] mb-2">Quote declined</h2>
                    <p class="text-[#647082] text-[14px]">No problem — if you change your mind or want a different scope, WhatsApp us and we'll sort out a new quote.</p>
                    <a href="https://wa.me/27815274711" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 mt-5 bg-[#081d3a] text-white font-semibold px-6 py-3 rounded-xl hover:bg-[#0d2a4a] transition-colors text-[13px]">
                        WhatsApp Us
                    </a>
                </div>
            @elseif ($booking->status->value === 'expired')
                <div class="mt-8 bg-white rounded-2xl border border-gray-100 p-6 sm:p-8 text-center">
                    <h2 class="text-[#081d3a] font-bold text-[18px] mb-2">This quote has expired</h2>
                    <p class="text-[#647082] text-[14px]">It's been a little while, so we've released this slot. WhatsApp us and we'll get you a fresh quote and check what's still available.</p>
                    <a href="https://wa.me/27815274711" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 mt-5 bg-[#081d3a] text-white font-semibold px-6 py-3 rounded-xl hover:bg-[#0d2a4a] transition-colors text-[13px]">
                        WhatsApp Us
                    </a>
                </div>
            @else
                <div class="mt-8 bg-white rounded-2xl border border-gray-100 p-6 sm:p-8 text-center">
                    <p class="text-[#647082] text-[14px]">This quote has already been actioned.</p>
                </div>
            @endif

        </div>
    </section>

    @include('components.footer')
@endsection
