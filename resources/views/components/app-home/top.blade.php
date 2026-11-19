{{-- Phone homepage, top part (app layout): pill header, photo hero, service tiles, how it works, reviews and the
     suburb check. Shown below 1024px only (welcome.blade.php); computers get the classic hero. Calls openQuote()
     on the app shell in welcome.blade.php. --}}
@php
    $services = \App\Support\QuoteFlow::services();
    $reviews = collect(config('reviews'))->reverse()->values();
@endphp
{{-- Top bar: logo and call in a floating frosted pill from the start, same as the real site's phone navbar
     (styles match #mainNav on phones in app.css). --}}
<header class="fixed inset-x-0 top-0 z-40 px-3 pt-2">
    <div class="app-pill is-pill flex h-14 items-center justify-between px-4">
    <a href="/" class="press"><img src="/images/logo-light.png" alt="SpringKleaners" class="h-9 w-auto"></a>
    <a href="tel:+27815274711" class="press flex h-10 items-center gap-2 rounded-full border border-white/30 px-3.5 text-[13px] font-semibold text-white" aria-label="Call us">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.95.68l1.5 4.5a1 1 0 01-.5 1.21l-2.26 1.13a11 11 0 005.52 5.52l1.13-2.26a1 1 0 011.21-.5l4.5 1.5a1 1 0 01.68.95V19a2 2 0 01-2 2h-1C9.72 21 3 14.28 3 6V5z"/></svg>
        Call
    </a>
    </div>
</header>

{{-- Hero: proof and promise, kept short so the choice below shows on the first screen --}}
<section class="relative isolate overflow-hidden bg-[#17171a] px-4 pb-7 pt-24">
    {{-- Computers never show this hero, so they get a 1-pixel placeholder instead of downloading the photo. --}}
    <picture>
        <source media="(min-width: 1024px)" srcset="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7">
        <img src="/images/works/hero/slide-kitchen-828.avif" alt="A spotless, deep-cleaned kitchen with a navy island" fetchpriority="high"
         class="absolute inset-0 -z-10 h-full w-full object-cover opacity-40" style="object-position: 40% center;">
    </picture>
    <p class="mb-3 flex items-center gap-2.5 text-[13px] font-medium text-white">
        <x-stars />
        <span><b>4.9</b> on Google</span><span class="h-3.5 w-px bg-white/40"></span><span><b>500+</b> cleans done</span>
    </p>
    <h1 class="font-display text-[34px] leading-[1.05] text-white" style="letter-spacing:-0.03em">Cape Town's Northern Suburbs, cleaned properly.</h1>
    <p class="mt-2.5 text-[15px] leading-relaxed text-white/90">Your price in a minute. Weekend slots. No payment upfront.</p>
</section>

{{-- The main thing on the page: what needs cleaning? One tap opens booking with it chosen. --}}
<section id="app-services" class="relative -mt-4 scroll-mt-20 rounded-t-3xl bg-white px-4 pb-8 pt-6">
    <h2 class="font-display text-[24px] leading-none text-[#081d3a]">What needs cleaning?</h2>
    <p class="mt-1.5 text-[14px] text-[#5b6779]">Tap one to see your price.</p>
    <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-3">
        @foreach ($services as $slug => $s)
            <button type="button" x-on:click="openQuote({ service: @js($slug) })"
                    class="press overflow-hidden rounded-2xl border border-[#081d3a]/10 bg-white text-left">
                <img src="{{ $s['img'] }}" alt="{{ $s['alt'] }}" loading="lazy" class="aspect-[4/3] w-full object-cover">
                <span class="block px-3 pb-3 pt-2.5">
                    <span class="block text-[15px] font-bold leading-tight text-[#081d3a]">{{ $s['short'] }}</span>
                    <span class="mt-0.5 block text-[12.5px] text-[#5b6779]">from R{{ number_format($s['base_price'] + $s['service_fee']) }}</span>
                </span>
            </button>
        @endforeach
    </div>
    <div class="mt-4 grid grid-cols-3 rounded-2xl bg-[#081d3a] px-2 py-3 text-center text-[12px] text-white/85">
        <span>Vetted &amp; insured</span><span class="border-x border-white/20">Weekends</span><span>Pay after</span>
    </div>
</section>

{{-- How it works --}}
<section class="bg-[#f6f8fb] px-4 py-8">
    <h2 class="font-display text-[24px] leading-none text-[#081d3a]">How it works</h2>
    <p class="mb-4 mt-1.5 text-[14px] text-[#5b6779]">Three steps, about two minutes.</p>
    <ol class="grid grid-cols-3 gap-3">
        @foreach ([['Get your price', 'In about a minute'], ['Pick a slot', 'Sat or Sun, morning or afternoon'], ['We clean', 'You check it before we go']] as $i => [$t, $d])
            <li>
                <span class="mb-2 block font-display text-[26px] leading-none text-[#081d3a]/25">0{{ $i + 1 }}</span>
                <p class="text-[14px] font-bold leading-tight text-[#081d3a]">{{ $t }}</p>
                <p class="mt-1 text-[12px] leading-snug text-[#5b6779]">{{ $d }}</p>
            </li>
        @endforeach
    </ol>
</section>

{{-- Reviews: swipe, with dots for where you are --}}
<section class="py-8" x-data="{ i: 0, n: {{ $reviews->count() + 1 }} }">
    <div class="flex items-end justify-between px-4">
        <h2 class="font-display text-[24px] leading-none text-[#081d3a]">What clients say</h2>
        <a href="https://www.google.com/maps/search/?api=1&query=Spring+Kleaners+1+Stepney+Rd+Parklands+Cape+Town" target="_blank" rel="noopener noreferrer" class="text-[13px] font-semibold text-[#081d3a] underline decoration-[#081d3a]/25 underline-offset-4">See all</a>
    </div>
    <p class="mb-4 mt-1.5 px-4 text-[14px] text-[#5b6779]">Real reviews from Google and Bark.</p>
    <div class="no-bar flex snap-x snap-mandatory gap-3 overflow-x-auto scroll-px-4 px-4"
         x-on:scroll.passive.debounce.40ms="i = Math.min(n - 1, Math.round($el.scrollLeft / ($el.children[1].offsetWidth + 12)))">
        <div class="flex w-[46%] flex-shrink-0 snap-start flex-col justify-center rounded-2xl bg-[#081d3a] p-4 text-white">
            <span class="font-display text-[44px] leading-none">4.9</span>
            <x-stars class="mt-2 h-4 w-[88px]" />
            <p class="mt-2 text-[12px] text-white/75">Google &amp; Bark reviews</p>
        </div>
        @foreach ($reviews as $r)
            <figure class="flex w-[78%] flex-shrink-0 snap-start flex-col rounded-2xl border border-[#081d3a]/10 p-4">
                <blockquote class="flex-1 text-[14px] leading-relaxed text-[#081d3a]">&ldquo;{{ $r['quote'] }}&rdquo;</blockquote>
                <figcaption class="mt-3 flex items-center gap-2.5">
                    <span class="grid h-9 w-9 flex-shrink-0 place-items-center rounded-full bg-[#081d3a] text-[12px] font-bold text-[#f6e304]" aria-hidden="true">{{ $r['initials'] }}</span>
                    <span class="min-w-0 text-[12.5px] leading-tight">
                        <b class="block text-[#081d3a]">{{ $r['name'] }}</b>
                        <span class="block truncate text-[#5b6779]">{{ $r['meta'] }}</span>
                    </span>
                </figcaption>
            </figure>
        @endforeach
    </div>
    <div class="mt-3 flex justify-center gap-1.5" aria-hidden="true">
        <template x-for="k in n" :key="k"><span class="h-1.5 rounded-full transition-all duration-200" :class="k - 1 === i ? 'w-4 bg-[#081d3a]' : 'w-1.5 bg-[#081d3a]/20'"></span></template>
    </div>
</section>

{{-- Suburb check: an instant answer, then book --}}
<section class="bg-[#081d3a] px-4 py-8 text-white"
         x-data="{ q: '', suburbs: @js(config('served_suburbs')), get match() { const v = this.q.trim().toLowerCase(); return v ? this.suburbs.find(s => s.toLowerCase() === v) || null : null }, get hints() { const v = this.q.trim().toLowerCase(); return v.length < 2 || this.match ? [] : this.suburbs.filter(s => s.toLowerCase().includes(v)).slice(0, 4) } }">
    <h2 class="font-display text-[24px] leading-none">Do we clean in your suburb?</h2>
    <p class="mt-1.5 text-[14px] text-white/70">32 areas across the Northern Suburbs.</p>
    <input type="text" x-model="q" placeholder="Type your suburb" autocomplete="address-level2" enterkeyhint="search"
           class="mt-4 w-full rounded-xl border border-white/20 bg-white px-4 py-3.5 text-[16px] text-[#081d3a] placeholder-[#081d3a]/40 focus:border-[#f6e304] focus:outline-none">
    <div class="mt-2 flex flex-wrap gap-2" x-show="hints.length">
        <template x-for="s in hints" :key="s"><button type="button" x-on:click="q = s" class="press rounded-full bg-white/10 px-3 py-1.5 text-[13px]" x-text="s"></button></template>
    </div>
    <div x-show="match" x-cloak class="mt-4 flex items-center justify-between gap-3 rounded-xl bg-white/10 p-3.5">
        <p class="text-[14px]"><b>Yes</b>, we clean in <span x-text="match"></span>.</p>
        <button type="button" x-on:click="openQuote({ suburb: match })" class="press flex-shrink-0 rounded-full bg-[#f6e304] px-4 py-2.5 text-[14px] font-bold text-[#081d3a]">Book</button>
    </div>
    <p x-show="q.trim().length > 2 && !match && !hints.length" x-cloak class="mt-4 text-[14px] text-white/80">Not yet, but we're growing. <a :href="@js(\App\Support\WhatsApp::base()) + encodeURIComponent('Hi SpringKleaners, do you clean in ' + q.trim() + '? Please let me know when you do.')" target="_blank" rel="noopener noreferrer" class="font-semibold text-[#f6e304] underline underline-offset-2">WhatsApp us</a> and we'll tell you when we reach you.</p>
</section>
