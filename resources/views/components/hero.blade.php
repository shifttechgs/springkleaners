@push('preload')
<link rel="preload" href="/fonts/geist-600.woff2" as="font" type="font/woff2" crossorigin>
@endpush
@php
    // Bookable services with their pricing, for the hero's instant quote (same formula as the full booking
    // form). If the database is unreachable the homepage still renders; the card then links to the full form.
    $heroServices = [];
    try {
        foreach (\App\Support\Services::bookable() as $slug => $svc) {
            $heroServices[$slug] = [
                'name' => $svc['name'],
                'base_price' => (float) $svc['base_price'],
                'included_bedrooms' => (int) $svc['included_bedrooms'],
                'included_bathrooms' => (int) $svc['included_bathrooms'],
                'bedroom_price' => (float) $svc['bedroom_price'],
                'bathroom_price' => (float) $svc['bathroom_price'],
                'extra_room_price' => (float) $svc['extra_room_price'],
                'service_fee' => (float) $svc['service_fee'],
            ];
        }
    } catch (\Throwable $e) {
        $heroServices = [];
    }
    // Hero slides: background photos that change with a striped reveal (see the hero slider script at the end).
    // `dim` is how much of the photo shows through on desktop, `dimMobile` on phones (lower = darker, for text contrast).
    // Set `enabled` to false to hide a slide. With one slide left, the counter, thumbnails and pause button are hidden too.
    $heroSlides = array_values(array_filter([
        ['enabled' => true, 'name' => 'slide-kitchen', 'label' => 'Deep Cleaning', 'alt' => 'A spotless, deep-cleaned kitchen with a navy island', 'w' => 2560, 'h' => 1706, 'pos' => '40% center', 'dim' => 0.44, 'dimMobile' => 0.42],
        ['enabled' => false, 'name' => 'slide-movein', 'label' => 'Move-in Ready', 'alt' => 'A bright, move-in ready kitchen with white cabinets and a grey island', 'w' => 2560, 'h' => 1707, 'pos' => '45% center', 'dim' => 0.4, 'dimMobile' => 0.38],
        ['enabled' => false, 'name' => 'slide-postcon', 'label' => 'Post Construction', 'alt' => 'A mop bucket, broom and ladder outside a newly built house, ready for the post-construction clean', 'w' => 2560, 'h' => 1707, 'pos' => '60% center', 'dim' => 0.36, 'dimMobile' => 0.34],
    ], fn (array $slide) => $slide['enabled']));
    $heroHasSlideshow = count($heroSlides) > 1;
@endphp
{{-- overflow: clip (with overflow-hidden as the fallback) so the zooming background can never make the hero
     scroll sideways inside itself — e.g. when a field in the quote card gets focus. --}}
<section class="hero-compact relative isolate flex items-end min-h-[100svh] overflow-hidden bg-[#17171a] pt-28 pb-10 lg:pt-32 lg:pb-16" style="overflow: clip;">

    {{-- Background slides. Each photo fades into the near-black base (mask on .hero-photo) and drifts slowly while on
         screen; the next one arrives through horizontal stripes. Decorative, so hidden from screen readers. --}}
    <div class="absolute inset-x-0 top-0 -z-30 h-[100svh] lg:inset-0 lg:h-full" data-hero-slider aria-hidden="true">
        @foreach ($heroSlides as $i => $slide)
            <div class="hero-slide {{ $i === 0 ? 'is-on' : '' }}" style="--dim: {{ $slide['dim'] }}; --dim-m: {{ $slide['dimMobile'] }};" data-label="{{ $slide['label'] }}">
                <div class="hero-slide-zoom">
                    <img src="/images/works/hero/{{ $slide['name'] }}-1280.avif"
                         srcset="/images/works/hero/{{ $slide['name'] }}-828.avif 828w, /images/works/hero/{{ $slide['name'] }}-1280.avif 1280w, /images/works/hero/{{ $slide['name'] }}-1920.avif 1920w, /images/works/hero/{{ $slide['name'] }}-2560.avif 2560w"
                         sizes="(max-width: 1023px) 165vh, 100vw"
                         width="{{ $slide['w'] }}" height="{{ $slide['h'] }}"
                         fetchpriority="{{ $i === 0 ? 'high' : 'low' }}" decoding="async"
                         alt="{{ $slide['alt'] }}"
                         class="hero-photo h-full w-full object-cover" style="object-position: {{ $slide['pos'] }};">
                </div>
            </div>
        @endforeach
        {{-- Desktop: a plain linear fade (no glow, no spotlight) that darkens only the lower-left, where the white
             text sits, so the bright kitchen stays bright at the top right. --}}
        <div class="hero-scrim" aria-hidden="true"></div>
    </div>

    <div class="section-wrap w-full">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 xl:gap-16 items-end">

            <div class="lg:col-span-7 lg:pb-3">
                {{-- Proof first: the rating and the number of cleans sit right above the headline, where they're read. --}}
                <p id="hero-badge" class="hero-settle mb-7 flex flex-wrap items-center gap-x-2.5 sm:gap-x-3 gap-y-1.5 text-[13px] sm:text-[14px] font-medium text-white">
                    <svg class="h-[13px] w-[72px] sm:h-4 sm:w-[88px] flex-shrink-0 text-[#f6e304]" viewBox="0 0 88 16" fill="currentColor" role="img" aria-label="Rated 4.9 out of 5">
                        <defs><path id="hero-star" d="M8 .6l2.2 4.9 5.3.5-4 3.6 1.2 5.3L8 12.2 3.3 14.9l1.2-5.3-4-3.6 5.3-.5z"/></defs>
                        <use href="#hero-star"/><use href="#hero-star" x="18"/><use href="#hero-star" x="36"/><use href="#hero-star" x="54"/><use href="#hero-star" x="72"/>
                    </svg>
                    <span><span class="font-bold">4.9</span> on Google</span>
                    <span class="h-3.5 w-px bg-white/40" aria-hidden="true"></span>
                    <span><span class="font-bold">500+</span> cleans done</span>
                </p>

                <h1 id="hero-headline" class="font-display text-white text-[40px] sm:text-[58px] lg:text-[62px] xl:text-[76px] 2xl:text-[84px] min-[2400px]:text-[100px] leading-[1.04] mb-8">
                    <span class="hero-line"><span>Cape Town's</span></span>
                    <span class="hero-line"><span>Northern Suburbs,</span></span>
                    <span class="hero-line"><span>cleaned properly.</span></span>
                </h1>

                <p id="hero-sub" class="hero-settle max-w-[34rem] lg:max-w-[38rem] 2xl:max-w-[44rem] text-[17px] lg:text-[19px] 2xl:text-[21px] leading-relaxed text-white/90 mb-9 lg:mb-0">
                    Deep cleaning, end-of-tenancy and post-construction cleaning for homes and businesses, with your price before you book.
                </p>

                <div class="hero-settle flex flex-wrap items-center gap-x-6 gap-y-3 mb-10 lg:hidden">
                    <x-arrow-button href="#hero-form">Get my instant quote</x-arrow-button>
                    <a href="#services" class="link-chip link-chip--down text-[15px] font-semibold text-white">See our services<span class="btn-arrow__chip link-chip__chip" aria-hidden="true"><svg class="btn-arrow__icon btn-arrow__icon--out" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M6 13l6 6 6-6"/></svg><svg class="btn-arrow__icon btn-arrow__icon--in" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M6 13l6 6 6-6"/></svg></span></a>
                </div>
            </div>

            <div class="lg:col-span-5 scroll-mt-24 lg:scroll-mt-0" id="hero-form" data-hide-mobile-bar>
                @if (empty($heroServices))
                    <div class="bg-[#f6f8fb] rounded-xl p-6 sm:p-7 ring-1 ring-white/30">
                        <p class="font-display text-[#081d3a] text-[26px] leading-none mb-3">Get your instant quote</p>
                        <x-arrow-button block href="{{ route('booking.show') }}">Start my quote</x-arrow-button>
                    </div>
                @else
                {{-- Instant quote + booking in four short steps, right here in the hero: what & where, size (live
                     price), when (real availability), contact. It submits to the same endpoint as the full booking
                     form, so slot rules, alerts and emails all apply. Everything goes in quote-safe (@js / Js::from). --}}
                <div x-data="{
                    step: 1,
                    done: false,
                    submitting: false,
                    serverError: '',
                    suburbs: @js(config('served_suburbs')),
                    suburbQuery: '',
                    filteredSuburbs: [],
                    showSuggestions: false,
                    selectedSuburb: '',
                    locationStatus: null,
                    waitlistEmail: '',
                    services: @js($heroServices),
                    serviceKey: @js(array_key_exists('deep-cleaning', $heroServices) ? 'deep-cleaning' : (array_key_first($heroServices) ?? '')),
                    propertyType: 'House',
                    bedrooms: 2, bathrooms: 1, extraRooms: 0,
                    date: '', slot: '',
                    slotLabels: @js(\App\Models\Booking::TIME_SLOTS),
                    bookableDays: @js(\App\Models\Booking::BOOKABLE_DAYS),
                    counts: {}, taken: {}, capacity: 2, loadingDates: false, loadedMonths: [],
                    form: { name: '', phone: '', email: '', address: '' },
                    photoConsent: false,
                    errors: {},
                    availabilityUrl: @js(route('booking.availability')),
                    reserveUrl: @js(route('booking.reserve')),
                    fullFormUrl: @js(route('booking.show')),
                    stepNames: ['Your clean', 'Size & price', 'Date & time', 'Your details'],
                    started: false, reached: [1],

                    // Analytics (GA4, via the gtag already on every page): a funnel of quote_start → quote_step 2/3/4
                    // → generate_lead, plus where people drop out. Never sends name, phone, email, address or typed text.
                    track(name, params = {}) {
                        try { if (typeof window.gtag === 'function') window.gtag('event', name, Object.assign({ form_location: 'hero' }, params)); } catch (e) {}
                    },
                    start() { if (this.started) return; this.started = true; this.track('quote_start', { service: this.serviceKey }); },

                    get service() { return this.services[this.serviceKey] || null },
                    get subtotal() {
                        const s = this.service; if (!s) return 0;
                        return s.base_price
                            + Math.max(0, this.bedrooms - s.included_bedrooms) * s.bedroom_price
                            + Math.max(0, this.bathrooms - s.included_bathrooms) * s.bathroom_price
                            + this.extraRooms * s.extra_room_price;
                    },
                    get total() { return this.subtotal + (this.service ? this.service.service_fee : 0) },
                    money(n) { return 'R' + Math.round(n).toLocaleString('en-US') },
                    sizeLabel(n, max) { return n >= max ? max + '+' : String(n) },

                    filterSuburbs() {
                        this.locationStatus = null;
                        if (this.suburbQuery.length < 1) { this.filteredSuburbs = []; this.showSuggestions = false; return; }
                        const q = this.suburbQuery.toLowerCase();
                        this.filteredSuburbs = this.suburbs.filter(s => s.toLowerCase().includes(q));
                        this.showSuggestions = this.filteredSuburbs.length > 0;
                    },
                    selectSuburb(s) { this.selectedSuburb = s; this.suburbQuery = s; this.showSuggestions = false; this.locationStatus = 'valid'; this.errors.suburb = false; },
                    checkLocation() {
                        this.showSuggestions = false;
                        if (!this.suburbQuery.trim()) { this.locationStatus = 'required'; return; }
                        const m = this.suburbs.find(s => s.toLowerCase() === this.suburbQuery.trim().toLowerCase());
                        if (m) { this.selectSuburb(m); } else { this.locationStatus = 'invalid'; this.track('quote_out_of_area'); }
                    },

                    next() {
                        this.serverError = '';
                        if (this.step === 1) {
                            if (this.locationStatus !== 'valid') this.checkLocation();
                            this.errors.service = !this.service;
                            if (this.locationStatus !== 'valid' || this.errors.service) return;
                        }
                        if (this.step === 3) {
                            this.errors.when = !this.date || !this.slot;
                            if (this.errors.when) return;
                        }
                        this.go(this.step + 1);
                    },
                    back() { if (this.step > 1) this.go(this.step - 1) },
                    // Close the success card: back to a fresh step 1 for another booking. Availability is fetched again
                    // so the slot just booked shows as taken.
                    reset() {
                        Object.assign(this, {
                            done: false, step: 1, serverError: '', errors: {},
                            suburbQuery: '', selectedSuburb: '', locationStatus: null, showSuggestions: false,
                            propertyType: 'House', bedrooms: 2, bathrooms: 1, extraRooms: 0,
                            date: '', slot: '', loadedMonths: [], counts: {}, taken: {},
                            form: { name: '', phone: '', email: '', address: '' }, photoConsent: false,
                            started: false, reached: [1],
                        });
                        this.$nextTick(() => { this.keepInView(); this.$refs.title?.focus({ preventScroll: true }); });
                    },
                    go(n) {
                        this.step = n;
                        // Count each step once per visit, so going Back and forward again doesn't inflate the funnel.
                        if (!this.reached.includes(n)) {
                            this.reached.push(n);
                            this.track('quote_step', { step_number: n, step_name: this.stepNames[n - 1], service: this.serviceKey, property_type: this.propertyType, value: Math.round(this.total), currency: 'ZAR' });
                        }
                        if (n === 3) this.loadDates();
                        this.$nextTick(() => {
                            this.keepInView();
                            // No auto-focus on touch screens: focusing a text field would pop the keyboard up unasked.
                            if (!window.matchMedia('(pointer: coarse)').matches) this.$refs['step' + n]?.querySelector('input, button, select')?.focus({ preventScroll: true });
                        });
                    },
                    // Phones: after a step change the card's top (title, progress, price) can sit under the floating
                    // navbar, so bring it back into view.
                    keepInView() {
                        if (window.innerWidth >= 1024) return;
                        const top = this.$root.getBoundingClientRect().top;
                        if (top < 88 || top > window.innerHeight * 0.5) window.scrollTo({ top: window.scrollY + top - 96, behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth' });
                    },

                    // Next bookable dates, with how many slots are left on each.
                    async loadDates() {
                        const now = new Date();
                        const months = [0, 1].map(k => { const d = new Date(now.getFullYear(), now.getMonth() + k, 1); return d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-01'; });
                        const todo = months.filter(m => !this.loadedMonths.includes(m));
                        if (!todo.length) return;
                        this.loadingDates = true;
                        try {
                            for (const m of todo) {
                                const json = await (await fetch(this.availabilityUrl + '?month=' + m)).json();
                                Object.assign(this.counts, json.counts || {});
                                Object.assign(this.taken, json.times || {});
                                this.capacity = json.capacity || this.capacity;
                                this.loadedMonths.push(m);
                            }
                        } catch (e) {}
                        this.loadingDates = false;
                    },
                    iso(d) { return d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0') },
                    get dateOptions() {
                        const out = [];
                        const d = new Date(); d.setHours(0, 0, 0, 0);
                        for (let i = 0; i < 62 && out.length < 6; i++, d.setDate(d.getDate() + 1)) {
                            if (!this.bookableDays.includes(d.getDay())) continue;
                            const iso = this.iso(d);
                            const used = Math.max(this.counts[iso] || 0, (this.taken[iso] || []).length);
                            out.push({ iso, day: d.toLocaleDateString('en-GB', { weekday: 'short' }), date: d.toLocaleDateString('en-GB', { day: 'numeric', month: 'short' }), left: Math.max(0, this.capacity - used) });
                        }
                        return out;
                    },
                    slotTaken(s) { return (this.taken[this.date] || []).includes(s) },
                    pickDate(o) { if (o.left < 1) return; this.date = o.iso; if (this.slot && this.slotTaken(this.slot)) this.slot = ''; this.errors.when = false; },
                    get whenLabel() {
                        if (!this.date) return '';
                        return new Date(this.date + 'T00:00:00').toLocaleDateString('en-GB', { weekday: 'short', day: 'numeric', month: 'short' }) + (this.slot ? ', ' + this.slot : '');
                    },
                    get fullFormLink() {
                        const p = new URLSearchParams();
                        if (this.serviceKey) p.set('service', this.serviceKey);
                        if (this.selectedSuburb) p.set('suburb', this.selectedSuburb);
                        return this.fullFormUrl + (p.toString() ? '?' + p.toString() : '');
                    },

                    async submit() {
                        if (this.submitting) return;
                        const f = this.form;
                        this.errors = {
                            name: !f.name.trim(),
                            phone: f.phone.replace(/\D/g, '').length < 9,
                            email: !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(f.email.trim()),
                            address: !f.address.trim(),
                        };
                        if (Object.values(this.errors).some(Boolean)) return;
                        this.submitting = true; this.serverError = '';
                        try {
                            const res = await fetch(this.reserveUrl, {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                                body: JSON.stringify({
                                    service: this.serviceKey, date: this.date, time: this.slot,
                                    name: f.name.trim(), phone: f.phone.trim(), email: f.email.trim(), address: f.address.trim(), suburb: this.selectedSuburb,
                                    property_type: this.propertyType, photo_consent: this.photoConsent,
                                    bedrooms: this.sizeLabel(this.bedrooms, 5), bathrooms: this.sizeLabel(this.bathrooms, 4), extra_rooms: this.sizeLabel(this.extraRooms, 4),
                                    booking_type: 'once-off', subtotal: this.subtotal, total: this.total,
                                }),
                            });
                            const json = await res.json().catch(() => ({}));
                            if (res.ok && json.status === 'ok') {
                                this.done = true;
                                this.track('generate_lead', { service: this.serviceKey, property_type: this.propertyType, value: Math.round(this.total), currency: 'ZAR' });
                                this.$nextTick(() => this.keepInView()); return;
                            }
                            this.track('quote_error', { reason: json.status || ('http_' + res.status), step_number: this.step });
                            if (['time_taken', 'full', 'invalid_day'].includes(json.status)) {
                                this.loadedMonths = []; await this.loadDates();
                                this.slot = ''; if (json.status !== 'time_taken') this.date = '';
                                this.serverError = json.status === 'time_taken' ? 'That slot was just booked — please pick another.' : 'That date just filled up — please pick another.';
                                this.step = 3; this.$nextTick(() => this.keepInView()); return;
                            }
                            this.serverError = json.message || 'Something went wrong — please try again, or WhatsApp us on +27 81 527 4711.';
                        } catch (e) {
                            this.track('quote_error', { reason: 'network', step_number: this.step });
                            this.serverError = 'Something went wrong — please try again, or WhatsApp us on +27 81 527 4711.';
                        } finally {
                            this.submitting = false;
                        }
                    },
                }" x-on:focusin="start()" x-on:pointerdown="start()" class="bg-[#f6f8fb] rounded-xl p-6 sm:p-7 shadow-[0_40px_90px_-30px_rgba(0,0,0,0.75)] ring-1 ring-white/30">

                    {{-- Done --}}
                    <div x-show="done" x-cloak class="relative text-center py-4" role="status" aria-live="polite">
                        <button type="button" x-on:click="reset()" aria-label="Close"
                                class="absolute -top-2 -right-2 grid h-10 w-10 place-items-center rounded-full text-[#081d3a]/60 hover:bg-[#081d3a]/[0.06] hover:text-[#081d3a] focus-visible:outline-2 focus-visible:outline-[#081d3a] transition-colors">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/></svg>
                        </button>
                        <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <p class="font-display text-[#081d3a] text-[24px] leading-tight">Booking request sent!</p>
                        <p class="text-[#5b6779] text-[14px] mt-2" x-text="(service ? service.name : '') + ' · ' + whenLabel"></p>
                        <p class="text-[#081d3a] font-bold text-[20px] mt-1" x-text="money(total)"></p>
                        <p class="text-[#5b6779] text-[13.5px] mt-3">We'll confirm on WhatsApp shortly, and a confirmation email is on its way to <span class="font-semibold text-[#081d3a]" x-text="form.email"></span>.</p>
                        <a href="https://wa.me/27815274711" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 mt-5 bg-[#25d366] text-[#081d3a] font-bold px-5 py-3 rounded-full text-[14px] hover:bg-[#20bd5a] transition-colors">WhatsApp us</a>
                    </div>

                    <div x-show="!done">
                        {{-- Header: title, live price, progress --}}
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p x-ref="title" tabindex="-1" class="font-display text-[#081d3a] text-[24px] sm:text-[26px] leading-none focus:outline-none" x-text="step > 1 && window.innerWidth < 640 ? 'Your quote' : 'Get your instant quote'">Get your instant quote</p>
                                <p class="hero-form-sub text-[#5b6779] text-[13.5px] mt-2">
                                    <span x-text="'Step ' + step + ' of 4'"></span> &middot; <span x-text="stepNames[step - 1]"></span>
                                </p>
                            </div>
                            <div x-show="step > 1" x-cloak class="flex-shrink-0 text-right" aria-live="polite">
                                <p class="text-[#5b6779] text-[11px] font-semibold uppercase tracking-wider">Your price</p>
                                <p class="text-[#081d3a] font-extrabold text-[22px] leading-none mt-0.5" x-text="money(total)"></p>
                            </div>
                        </div>
                        <div class="flex gap-1 mt-4 mb-4" aria-hidden="true">
                            <template x-for="n in 4" :key="n">
                                <span class="h-1 flex-1 rounded-full transition-colors duration-300" :class="n <= step ? 'bg-[#081d3a]' : 'bg-[#081d3a]/12'"></span>
                            </template>
                        </div>

                        {{-- Step 1: where & what --}}
                        <div x-show="step === 1" x-ref="step1" class="space-y-3">
                            <div>
                                <label for="hero-suburb" class="block text-[#081d3a]/80 text-[13px] mb-1.5 font-semibold">Your suburb <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <input id="hero-suburb" type="text" x-model="suburbQuery" x-on:input="filterSuburbs()" x-on:blur="setTimeout(() => { showSuggestions = false }, 200)"
                                           x-on:keydown.enter.prevent="checkLocation()" autocomplete="address-level2" placeholder="e.g. Milnerton, Parklands..."
                                           class="w-full bg-white border rounded-lg px-4 py-3 text-[#081d3a] text-[16px] lg:text-[14px] placeholder-[#081d3a]/35 focus:border-[#f6e304] focus:outline-none transition-colors"
                                           :class="locationStatus === 'required' || locationStatus === 'invalid' ? 'border-red-400' : 'border-[#081d3a]/15'">
                                    <div x-show="showSuggestions" class="absolute left-0 right-0 top-full mt-1 bg-[#081d3a] border border-white/10 rounded-xl z-50 max-h-48 overflow-y-auto" style="display:none;">
                                        <template x-for="s in filteredSuburbs" :key="s">
                                            <div x-on:click="selectSuburb(s)" class="px-4 py-2.5 text-[14px] text-white/80 hover:bg-white/5 hover:text-[#f6e304] cursor-pointer" x-text="s"></div>
                                        </template>
                                    </div>
                                </div>
                                <p x-show="locationStatus === 'required'" x-cloak class="mt-1.5 text-red-600 text-[12.5px]">Please enter your suburb.</p>
                                <p x-show="locationStatus === 'valid'" x-cloak class="mt-1.5 text-green-600 text-[12.5px]">Great! We service <span class="font-semibold" x-text="selectedSuburb"></span>.</p>
                                <div x-show="locationStatus === 'invalid'" x-cloak class="mt-2 p-3 bg-[#081d3a]/5 rounded-lg border border-[#081d3a]/10">
                                    <p class="text-[#081d3a] font-semibold text-[13px]">We're not in <span x-text="suburbQuery || 'your area'"></span> yet</p>
                                    <p class="text-[#5b6779] text-[12px] mt-0.5">We're expanding — WhatsApp us and we'll let you know when we reach you.</p>
                                </div>
                                <div x-show="locationStatus !== 'valid' && locationStatus !== 'invalid' && !showSuggestions" class="hero-popular mt-2 flex flex-wrap gap-1.5">
                                    <template x-for="s in ['Milnerton', 'Blouberg', 'Table View', 'Century City', 'Sandown']" :key="s">
                                        <button type="button" x-on:click="selectSuburb(s)" class="px-3 py-1.5 lg:px-2 lg:py-1 bg-[#081d3a]/5 border border-[#081d3a]/10 rounded-full text-[12.5px] lg:text-[11px] text-[#081d3a]/70 hover:bg-[#f6e304] hover:border-[#f6e304] hover:text-[#081d3a] transition-colors" x-text="s"></button>
                                    </template>
                                </div>
                            </div>
                            <div>
                                <p id="hero-service-label" class="block text-[#081d3a]/80 text-[13px] mb-1.5 font-semibold">Service <span class="text-rose-500">*</span></p>
                                {{-- Custom listbox (a native select's open list can't be styled). Keyboard: arrows, Enter, Escape. --}}
                                <div class="relative"
                                     x-data="{
                                         open: false,
                                         active: 0,
                                         get keys() { return Object.keys(this.services) },
                                         toggle() { this.open ? this.close() : this.show() },
                                         show() { this.open = true; this.active = Math.max(0, this.keys.indexOf(this.serviceKey)); this.scrollActive() },
                                         close(refocus = false) { this.open = false; if (refocus) this.$refs.trigger.focus() },
                                         move(d) { if (!this.open) return this.show(); this.active = (this.active + d + this.keys.length) % this.keys.length; this.scrollActive() },
                                         scrollActive() { this.$nextTick(() => this.$refs.list.querySelectorAll('[role=option]')[this.active]?.scrollIntoView({ block: 'nearest' })) },
                                         choose(key) { this.serviceKey = key; this.errors.service = false; this.close(true) },
                                     }"
                                     x-on:keydown.escape.prevent.stop="close(true)"
                                     x-on:click.outside="close()">
                                    <button type="button" id="hero-service" x-ref="trigger"
                                            aria-haspopup="listbox" :aria-expanded="open" aria-labelledby="hero-service-label hero-service"
                                            x-on:click="toggle()"
                                            x-on:keydown.arrow-down.prevent="move(1)" x-on:keydown.arrow-up.prevent="move(-1)"
                                            x-on:keydown.enter.prevent="open ? choose(keys[active]) : show()"
                                            x-on:keydown.space.prevent="open ? choose(keys[active]) : show()"
                                            class="w-full flex items-center justify-between gap-3 bg-white border rounded-lg pl-4 pr-3 py-3 text-left transition-colors cursor-pointer focus:outline-none"
                                            :class="errors.service ? 'border-red-400' : (open ? 'border-[#f6e304] ring-2 ring-[#f6e304]/30' : 'border-[#081d3a]/15 hover:border-[#081d3a]/35')">
                                        <span class="min-w-0 flex items-baseline gap-2">
                                            <span class="truncate text-[14px] font-semibold text-[#081d3a]" x-text="service ? service.name : 'Choose a service'"></span>
                                            <span x-show="service" class="flex-shrink-0 text-[12px] text-[#5b6779]" x-text="service ? 'from ' + money(service.base_price + service.service_fee) : ''"></span>
                                        </span>
                                        <svg class="h-4 w-4 flex-shrink-0 text-[#081d3a]/55 transition-transform duration-200" :class="open && 'rotate-180'" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                    <ul x-show="open" x-cloak x-ref="list" role="listbox" aria-labelledby="hero-service-label"
                                        x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                                        x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                        class="absolute left-0 right-0 top-full z-50 mt-1.5 max-h-64 overflow-y-auto rounded-xl bg-white p-1.5 shadow-[0_24px_60px_-16px_rgba(8,29,58,0.45)] ring-1 ring-[#081d3a]/10">
                                        <template x-for="(key, i) in keys" :key="key">
                                            <li role="option" :aria-selected="key === serviceKey" :id="'hero-service-' + key"
                                                x-on:click="choose(key)" x-on:mouseenter="active = i"
                                                class="flex items-center justify-between gap-3 rounded-lg px-3 py-2.5 cursor-pointer transition-colors"
                                                :class="active === i ? 'bg-[#081d3a]/[0.06]' : ''">
                                                <span class="min-w-0">
                                                    <span class="block truncate text-[14px] font-semibold text-[#081d3a]" x-text="services[key].name"></span>
                                                    <span class="block text-[12px] text-[#5b6779]" x-text="'from ' + money(services[key].base_price + services[key].service_fee)"></span>
                                                </span>
                                                <svg x-show="key === serviceKey" class="h-4 w-4 flex-shrink-0 text-[#081d3a]" fill="none" stroke="currentColor" stroke-width="2.6" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                                <a href="{{ route('services.show', 'office-commercial-cleaning') }}" class="inline-block mt-1.5 text-[12px] text-[#5b6779] underline decoration-[#081d3a]/20 underline-offset-2 hover:text-[#081d3a]">Office or commercial space? Get a custom quote</a>
                            </div>
                            <div class="flex gap-1 rounded-full bg-[#081d3a]/[0.07] p-1" role="group" aria-label="Property type">
                                <template x-for="t in ['House', 'Apartment / Flat', 'Townhouse']" :key="t">
                                    <button type="button" x-on:click="propertyType = t" :aria-pressed="propertyType === t"
                                            :class="propertyType === t ? 'bg-[#081d3a] text-white shadow' : 'text-[#081d3a]/70 hover:text-[#081d3a]'"
                                            class="flex-1 px-2 py-2 rounded-full text-[13px] font-semibold transition-colors" x-text="t === 'Apartment / Flat' ? 'Apartment' : t"></button>
                                </template>
                            </div>
                        </div>

                        {{-- Step 2: size, with the price updating live --}}
                        <div x-show="step === 2" x-cloak x-ref="step2" class="space-y-2.5">
                            <template x-for="row in [
                                { key: 'bedrooms', label: 'Bedrooms', min: 1, max: 5 },
                                { key: 'bathrooms', label: 'Bathrooms', min: 1, max: 4 },
                                { key: 'extraRooms', label: 'Extra rooms', hint: 'Living, dining, study…', min: 0, max: 4 },
                            ]" :key="row.key">
                                <div class="flex items-center justify-between gap-3 bg-white border border-[#081d3a]/10 rounded-lg px-4 py-2.5">
                                    <div>
                                        <p class="text-[#081d3a] text-[14px] font-semibold" x-text="row.label"></p>
                                        <p x-show="row.hint" class="text-[#5b6779] text-[11.5px]" x-text="row.hint"></p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button type="button" x-on:click="$data[row.key] = Math.max(row.min, $data[row.key] - 1)" :disabled="$data[row.key] <= row.min"
                                                class="w-11 h-11 lg:w-9 lg:h-9 rounded-full border border-[#081d3a]/15 text-[#081d3a] text-[18px] font-bold leading-none hover:border-[#081d3a] disabled:opacity-30" :aria-label="'Fewer ' + row.label.toLowerCase()">−</button>
                                        <span class="w-8 text-center text-[#081d3a] text-[16px] font-bold" x-text="sizeLabel($data[row.key], row.max)" aria-live="polite"></span>
                                        <button type="button" x-on:click="$data[row.key] = Math.min(row.max, $data[row.key] + 1)" :disabled="$data[row.key] >= row.max"
                                                class="w-11 h-11 lg:w-9 lg:h-9 rounded-full border border-[#081d3a]/15 text-[#081d3a] text-[18px] font-bold leading-none hover:border-[#081d3a] disabled:opacity-30" :aria-label="'More ' + row.label.toLowerCase()">+</button>
                                    </div>
                                </div>
                            </template>
                            <p class="text-[#5b6779] text-[12px] pt-1">
                                <span x-text="service ? service.name : ''"></span> &middot; includes the
                                <span x-text="service ? money(service.service_fee) : ''"></span> service fee. Your price is confirmed before we start — never without your sign-off.
                            </p>
                        </div>

                        {{-- Step 3: real availability --}}
                        <div x-show="step === 3" x-cloak x-ref="step3">
                            <p class="text-[#081d3a]/80 text-[13px] mb-1.5 font-semibold">Pick a date <span class="font-normal text-[#5b6779]">(weekends)</span></p>
                            <p x-show="loadingDates" class="flex items-center gap-2 text-[#5b6779] text-[13px] py-3" role="status"><svg class="animate-spin h-4 w-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" class="opacity-25"></circle><path d="M22 12a10 10 0 00-10-10" stroke="currentColor" stroke-width="3" stroke-linecap="round"></path></svg> Checking availability…</p>
                            <div x-show="!loadingDates" class="grid grid-cols-3 gap-1.5">
                                <template x-for="o in dateOptions" :key="o.iso">
                                    <button type="button" x-on:click="pickDate(o)" :disabled="o.left < 1" :aria-pressed="date === o.iso"
                                            class="rounded-lg border px-2 py-2 text-center transition-colors disabled:opacity-40 disabled:cursor-not-allowed"
                                            :class="date === o.iso ? 'bg-[#081d3a] border-[#081d3a] text-white' : 'bg-white border-[#081d3a]/12 text-[#081d3a] hover:border-[#081d3a]'">
                                        <span class="block text-[11px] font-semibold opacity-70" x-text="o.day"></span>
                                        <span class="block text-[14px] font-bold leading-tight" x-text="o.date"></span>
                                        <span class="block text-[10.5px] mt-0.5" :class="date === o.iso ? 'text-white/70' : (o.left ? 'text-emerald-700' : 'text-rose-600')" x-text="o.left ? (o.left === 1 ? '1 slot left' : o.left + ' slots') : 'Full'"></span>
                                    </button>
                                </template>
                            </div>
                            <p class="text-[#081d3a]/80 text-[13px] mt-3 mb-1.5 font-semibold">Pick a time</p>
                            <div class="grid grid-cols-2 gap-1.5">
                                <template x-for="(s, i) in slotLabels" :key="s">
                                    <button type="button" x-on:click="slot = s; errors.when = false" :disabled="!date || slotTaken(s)" :aria-pressed="slot === s"
                                            class="rounded-lg border px-3 py-2.5 text-left transition-colors disabled:opacity-40 disabled:cursor-not-allowed"
                                            :class="slot === s ? 'bg-[#081d3a] border-[#081d3a] text-white' : 'bg-white border-[#081d3a]/12 text-[#081d3a] hover:border-[#081d3a]'">
                                        <span class="block text-[13px] font-bold" x-text="i === 0 ? 'Morning' : 'Afternoon'"></span>
                                        <span class="block text-[11.5px] opacity-75" x-text="date && slotTaken(s) ? 'Booked' : s"></span>
                                    </button>
                                </template>
                            </div>
                            <p x-show="errors.when" x-cloak class="mt-2 text-red-600 text-[12.5px]">Please pick a date and a time.</p>
                        </div>

                        {{-- Step 4: contact, then book --}}
                        <div x-show="step === 4" x-cloak x-ref="step4" class="relative space-y-2.5" :aria-busy="submitting ? 'true' : 'false'">
                            {{-- Loader: covers the fields while the booking is sent. --}}
                            <div x-show="submitting" x-cloak x-transition.opacity.duration.150ms
                                 class="absolute -inset-1 z-10 rounded-lg bg-[#f6f8fb]/85 backdrop-blur-[1px] flex flex-col items-center justify-center gap-3 text-[#081d3a]"
                                 role="status" aria-live="polite">
                                <svg class="animate-spin h-8 w-8" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" class="opacity-15"></circle><path d="M22 12a10 10 0 00-10-10" stroke="#081d3a" stroke-width="3" stroke-linecap="round"></path></svg>
                                <p class="text-[14px] font-semibold">Booking your clean…</p>
                                <p class="text-[12px] text-[#5b6779] -mt-2">Checking the slot is still free</p>
                            </div>
                            <div class="grid grid-cols-2 gap-2.5">
                                <div>
                                    <label for="hero-name" class="block text-[#081d3a]/80 text-[13px] mb-1 font-semibold">Full name <span class="text-rose-500">*</span></label>
                                    <input id="hero-name" type="text" x-model="form.name" x-on:input="errors.name = false" autocomplete="name" autocapitalize="words" enterkeyhint="next" placeholder="Jane Smith"
                                           class="w-full bg-white border rounded-lg px-3.5 py-2.5 text-[#081d3a] text-[16px] lg:text-[14px] placeholder-[#081d3a]/35 focus:border-[#f6e304] focus:outline-none" :class="errors.name ? 'border-red-400' : 'border-[#081d3a]/15'">
                                </div>
                                <div>
                                    <label for="hero-phone" class="block text-[#081d3a]/80 text-[13px] mb-1 font-semibold">Phone <span class="text-rose-500">*</span></label>
                                    <input id="hero-phone" type="tel" x-model="form.phone" x-on:input="errors.phone = false" autocomplete="tel" inputmode="tel" enterkeyhint="next" placeholder="082 xxx xxxx"
                                           class="w-full bg-white border rounded-lg px-3.5 py-2.5 text-[#081d3a] text-[16px] lg:text-[14px] placeholder-[#081d3a]/35 focus:border-[#f6e304] focus:outline-none" :class="errors.phone ? 'border-red-400' : 'border-[#081d3a]/15'">
                                </div>
                            </div>
                            <div>
                                <label for="hero-email" class="block text-[#081d3a]/80 text-[13px] mb-1 font-semibold">Email <span class="text-rose-500">*</span></label>
                                <input id="hero-email" type="email" x-model="form.email" x-on:input="errors.email = false" autocomplete="email" inputmode="email" autocapitalize="off" spellcheck="false" enterkeyhint="next" placeholder="you@email.com"
                                       class="w-full bg-white border rounded-lg px-3.5 py-2.5 text-[#081d3a] text-[16px] lg:text-[14px] placeholder-[#081d3a]/35 focus:border-[#f6e304] focus:outline-none" :class="errors.email ? 'border-red-400' : 'border-[#081d3a]/15'">
                            </div>
                            <div>
                                <label for="hero-address" class="block text-[#081d3a]/80 text-[13px] mb-1 font-semibold">Street address <span class="text-rose-500">*</span></label>
                                <input id="hero-address" type="text" x-model="form.address" x-on:input="errors.address = false" autocomplete="street-address" enterkeyhint="done" placeholder="e.g. 12 Beach Road"
                                       class="w-full bg-white border rounded-lg px-3.5 py-2.5 text-[#081d3a] text-[16px] lg:text-[14px] placeholder-[#081d3a]/35 focus:border-[#f6e304] focus:outline-none" :class="errors.address ? 'border-red-400' : 'border-[#081d3a]/15'">
                            </div>
                            <p x-show="errors.name || errors.phone || errors.email || errors.address" x-cloak class="text-red-600 text-[12.5px]">Please fill in the highlighted fields.</p>
                            <x-photo-consent model="photoConsent" id="hero-photo-consent" />
                            <p class="text-[#5b6779] text-[12.5px] bg-white border border-[#081d3a]/10 rounded-lg px-3 py-2">
                                <span class="font-semibold text-[#081d3a]" x-text="service ? service.name : ''"></span>
                                &middot; <span x-text="whenLabel"></span> &middot; <span x-text="selectedSuburb"></span>
                            </p>
                        </div>

                        <p x-show="serverError" x-cloak x-text="serverError" class="mt-3 text-red-600 text-[12.5px] font-semibold bg-red-50 rounded-lg px-3 py-2" role="alert"></p>

                        {{-- Navigation --}}
                        <div class="flex items-center gap-2 mt-4">
                            <button type="button" x-show="step > 1" x-cloak x-on:click="back()" :disabled="submitting"
                                    class="flex-shrink-0 inline-flex items-center justify-center w-12 h-12 rounded-full border border-[#081d3a]/15 text-[#081d3a] hover:border-[#081d3a] transition-colors disabled:opacity-40 disabled:cursor-not-allowed" aria-label="Back">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                            </button>
                            <div class="flex-1" x-show="step < 4">
                                <x-arrow-button block x-on:click="next()"><span x-text="step === 1 ? 'See my price' : (step === 2 ? 'Pick a date' : 'Almost done')">See my price</span></x-arrow-button>
                            </div>
                            <div class="flex-1" x-show="step === 4" x-cloak>
                                <x-arrow-button block x-on:click="submit()" x-bind:disabled="submitting" x-bind:aria-busy="submitting ? 'true' : 'false'" class="disabled:opacity-80 disabled:cursor-wait">
                                    <span class="inline-flex items-center gap-2">
                                        <span x-show="submitting" x-cloak class="inline-flex"><svg class="animate-spin h-4 w-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" class="opacity-25"></circle><path d="M22 12a10 10 0 00-10-10" stroke="currentColor" stroke-width="3" stroke-linecap="round"></path></svg></span>
                                        <span x-text="submitting ? 'Booking…' : 'Book now · ' + money(total)">Book now</span>
                                    </span>
                                </x-arrow-button>
                            </div>
                        </div>
                        <p class="hero-form-note text-center text-[#5b6779] text-[12px] mt-3">
                            <span x-show="step === 4">No payment now. We confirm every booking with you first.</span>
                            <span x-show="step < 4">Free, no obligation.</span>
                            <a :href="fullFormLink" x-on:click="track('quote_full_form', { step_number: step, service: serviceKey })" class="underline decoration-[#081d3a]/20 underline-offset-2 hover:text-[#081d3a]">Need add-ons or notes? Full form</a>
                        </p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Fact row: plain facts under a hairline, the way editorial heroes close. Text only, no icons or chips.
             Every claim here is stated elsewhere on the site (about page, reviews) — keep it that way. Shown when
             there is a single background photo; with 2+ slides the slide strip below takes this spot instead. --}}
        @if (! $heroHasSlideshow)
        <div class="hero-facts hero-settle mt-8 lg:mt-10 border-t border-white/15 pt-4 text-[12.5px] sm:text-[13px] text-white/85">
            <ul class="grid grid-cols-3 items-center lg:flex lg:whitespace-nowrap">
                <li class="pr-2 sm:pr-5 lg:pl-0"><span class="lg:hidden">Vetted &amp; insured</span><span class="hidden lg:inline">Background-checked, insured team</span></li>
                <li class="border-l border-white/25 px-2 sm:px-5 text-center lg:text-left"><span class="lg:hidden">Weekends</span><span class="hidden lg:inline">Saturday &amp; Sunday bookings</span></li>
                <li class="border-l border-white/25 px-2 sm:px-5 text-center lg:text-left">No upfront payment</li>
                <li class="hidden lg:block lg:ml-auto">
                    <a href="#services" class="link-chip link-chip--down link-chip--sm font-semibold text-white">Our services<span class="btn-arrow__chip link-chip__chip" aria-hidden="true"><svg class="btn-arrow__icon btn-arrow__icon--out" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M6 13l6 6 6-6"/></svg><svg class="btn-arrow__icon btn-arrow__icon--in" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M6 13l6 6 6-6"/></svg></span></a>
                </li>
            </ul>
        </div>
        @endif

        {{-- Slide label + counter, full width under the copy and the form. The line between the numbers fills while
             a slide is on screen and drives the autoplay. Only shown with 2+ slides: with one, it's just a label. --}}
        @if ($heroHasSlideshow)
        <div class="hero-strip hero-settle mt-8 lg:mt-10 flex items-center gap-3 sm:gap-4 whitespace-nowrap border-t border-white/15 pt-4 text-[13px] text-white/80">
            <span class="hidden min-[400px]:inline font-medium text-white">Cape Town</span>
            <span class="hidden min-[400px]:inline h-3.5 w-px bg-white/35" aria-hidden="true"></span>
            <span class="hero-roll" data-hero-label>{{ $heroSlides[0]['label'] }}</span>
            <span class="ml-2 sm:ml-4 flex items-center gap-3 tabular-nums" aria-hidden="true">
                <span data-hero-count>01</span>
                <span class="relative h-px w-12 overflow-hidden bg-white/25"><span class="hero-progress" data-hero-progress></span></span>
                <span>{{ str_pad(count($heroSlides), 2, '0', STR_PAD_LEFT) }}</span>
            </span>
            <span class="ml-auto flex items-center gap-3">
                @foreach ($heroSlides as $i => $slide)
                    <button type="button" class="hero-thumb hidden lg:flex {{ $i === 0 ? 'is-active' : '' }}" data-hero-thumb="{{ $i }}" aria-label="Show slide {{ $i + 1 }}: {{ $slide['label'] }}" @if ($i === 0) aria-current="true" @endif>
                        <img src="/images/works/hero/{{ $slide['name'] }}-828.avif" alt="{{ $slide['alt'] }}" width="80" height="52" loading="lazy" decoding="async" class="hero-thumb__img">
                    </button>
                @endforeach
                <button type="button" class="hero-pause" data-hero-pause aria-label="Pause background slideshow" aria-pressed="false">
                    <svg class="hero-pause__icon-pause h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M7 5h3v14H7zM14 5h3v14h-3z"/></svg>
                    <svg class="hero-pause__icon-play h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg>
                </button>
            </span>
        </div>
        @endif
    </div>
</section>

@push('scripts')
<script>
    // Hero slider: the incoming photo is revealed through horizontal stripes that open from the bottom up
    // (a mask redrawn each frame), and the progress line under the counter decides when to move on.
    (function () {
        var root = document.querySelector('[data-hero-slider]');
        if (!root) return;
        var slides = Array.prototype.slice.call(root.querySelectorAll('.hero-slide'));
        if (slides.length < 2) return;

        var thumbs = Array.prototype.slice.call(document.querySelectorAll('[data-hero-thumb]'));
        var pauses = Array.prototype.slice.call(document.querySelectorAll('[data-hero-pause]'));
        var label = document.querySelector('[data-hero-label]');
        var count = document.querySelector('[data-hero-count]');
        var progress = document.querySelector('[data-hero-progress]');
        var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        var BANDS = 30, REVEAL_MS = 1400;
        var index = 0, busy = false, paused = reduce;

        function stripes(t) {
            var size = 100 / BANDS, parts = [];
            for (var i = 0; i < BANDS; i++) {
                var start = (i / BANDS) * 0.55;
                var local = Math.min(1, Math.max(0, (t - start) / 0.45));
                var eased = 1 - Math.pow(1 - local, 3);
                var a = i * size, b = a + size * eased;
                parts.push('#000 ' + a + '%', '#000 ' + b + '%', 'transparent ' + b + '%', 'transparent ' + (a + size) + '%');
            }
            return 'linear-gradient(0deg, ' + parts.join(', ') + ')';
        }

        function setMask(el, value) {
            el.style.webkitMaskImage = value;
            el.style.maskImage = value;
        }

        function restartProgress() {
            if (!progress) return;
            progress.classList.remove('is-running');
            void progress.offsetWidth;
            progress.classList.add('is-running');
        }

        function updateUi() {
            if (label) {
                label.textContent = slides[index].getAttribute('data-label');
                label.classList.remove('is-rolling');
                void label.offsetWidth;
                label.classList.add('is-rolling');
            }
            if (count) count.textContent = (index + 1 < 10 ? '0' : '') + (index + 1);
            thumbs.forEach(function (t, i) {
                t.classList.toggle('is-active', i === index);
                if (i === index) t.setAttribute('aria-current', 'true'); else t.removeAttribute('aria-current');
            });
            restartProgress();
        }

        function go(next) {
            if (busy || next === index) return;
            var prev = slides[index], incoming = slides[next];
            index = next;
            prev.classList.remove('is-on');
            prev.classList.add('is-prev');
            incoming.classList.add('is-on');
            updateUi();

            if (reduce) {
                prev.classList.remove('is-prev');
                return;
            }
            busy = true;
            var t0 = null;
            setMask(incoming, stripes(0));
            requestAnimationFrame(function frame(now) {
                if (t0 === null) t0 = now;
                var t = Math.min(1, (now - t0) / REVEAL_MS);
                setMask(incoming, stripes(t));
                if (t < 1) return requestAnimationFrame(frame);
                setMask(incoming, '');
                prev.classList.remove('is-prev');
                busy = false;
            });
        }

        function setPaused(value) {
            paused = value;
            root.classList.toggle('is-paused', paused);
            if (progress) progress.classList.toggle('is-paused', paused);
            pauses.forEach(function (b) {
                b.setAttribute('aria-pressed', paused ? 'true' : 'false');
                b.setAttribute('aria-label', paused ? 'Play background slideshow' : 'Pause background slideshow');
                b.classList.toggle('is-paused', paused);
            });
        }

        if (progress) {
            progress.addEventListener('animationend', function () {
                if (!paused) go((index + 1) % slides.length);
            });
        }
        thumbs.forEach(function (t) {
            t.addEventListener('click', function () { go(parseInt(t.getAttribute('data-hero-thumb'), 10)); });
        });
        pauses.forEach(function (b) {
            b.addEventListener('click', function () { setPaused(!paused); });
        });

        setPaused(paused);
        restartProgress();
    })();
</script>
@endpush
