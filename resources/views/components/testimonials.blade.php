@php
    $reviews = collect(config('reviews'));
    $featured = $reviews->first();
    $others = $reviews->slice(1)->values();
@endphp
<section id="testimonials" class="bg-white section-py">
    <div class="section-wrap">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 xl:gap-16">

            {{-- Proof summary + the next step, kept in view while the reviews scroll past on desktop --}}
            <div class="lg:col-span-4">
                <div class="lg:sticky lg:top-28">
                    <div class="w-8 h-[2px] bg-[#f6e304] mb-3"></div>
                    <span class="text-[#081d3a] text-[11px] font-semibold uppercase tracking-[0.18em]">Customer Reviews</span>
                    <h2 class="text-4xl lg:text-5xl font-extrabold text-[#081d3a] tracking-tight leading-tight mt-3 mb-8">
                        Loved by<br>Cape Town<br>homeowners.
                    </h2>

                    <div class="flex items-center gap-4 mb-3">
                        <span class="font-display text-[64px] leading-none text-[#081d3a]">4.9</span>
                        <div>
                            <x-stars />
                            <p class="text-[#5b6779] text-[14px] mt-2">From real Google and Bark reviews</p>
                        </div>
                    </div>

                    <a href="https://www.google.com/maps/search/?api=1&query=Spring+Kleaners+1+Stepney+Rd+Parklands+Cape+Town"
                       target="_blank" rel="noopener noreferrer"
                       class="link-arrow text-[15px] font-semibold text-[#081d3a] underline decoration-[#081d3a]/30 underline-offset-4 hover:decoration-[#081d3a]">
                        Read them all on Google<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 17L17 7M8 7h9v9"/></svg>
                    </a>

                    <div class="mt-8">
                        <x-arrow-button :href="route('booking.show')">Get My Instant Quote</x-arrow-button>
                    </div>
                </div>
            </div>

            {{-- One review given room to breathe, the rest as a quiet ledger (no card boxes, no auto-scroll) --}}
            <div class="lg:col-span-8">
                @if ($featured)
                    <figure>
                        <blockquote class="font-display text-[26px] sm:text-[32px] leading-[1.22] text-[#081d3a]">
                            &ldquo;{{ $featured['quote'] }}&rdquo;
                        </blockquote>
                        <figcaption class="mt-7 flex items-center gap-4">
                            <span class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-full bg-[#081d3a] text-[13px] font-bold text-[#f6e304]">{{ $featured['initials'] }}</span>
                            <span class="leading-snug">
                                <span class="block text-[15px] font-semibold text-[#081d3a]">{{ $featured['name'] }}</span>
                                <span class="block text-[13px] text-[#5b6779]">{{ $featured['meta'] }}</span>
                            </span>
                        </figcaption>
                    </figure>
                @endif

                <div class="mt-12 grid grid-cols-1 md:grid-cols-2 md:gap-x-12 border-t border-[#081d3a]/10">
                    @foreach ($others as $r)
                        <figure class="border-b border-[#081d3a]/10 py-7">
                            <x-stars class="h-3.5 w-[77px]" />
                            <blockquote class="mt-3 text-[15px] leading-relaxed text-[#081d3a]/85">&ldquo;{{ $r['quote'] }}&rdquo;</blockquote>
                            <figcaption class="mt-4 text-[13px] leading-snug">
                                <span class="font-semibold text-[#081d3a]">{{ $r['name'] }}</span>
                                <span class="text-[#5b6779]"> &middot; {{ $r['meta'] }}</span>
                            </figcaption>
                        </figure>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</section>
