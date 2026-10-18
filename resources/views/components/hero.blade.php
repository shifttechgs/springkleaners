@push('preload')
<link rel="preload" href="/fonts/geist-600.woff2" as="font" type="font/woff2" crossorigin>
@endpush
@php
    // Names, taglines and prices come from the services table so the dropdown can't drift from the
    // real prices. If the database is unreachable the homepage still renders, using these defaults.
    $heroServices = [
        ['value' => 'deep-cleaning', 'name' => 'Deep Cleaning', 'short' => 'Deep Cleaning', 'note' => 'A thorough top-to-bottom clean of every surface, corner and room.', 'price' => 'R1,200', 'unit' => 'visit'],
        ['value' => 'end-of-tenancy', 'name' => 'End-of-Tenancy Cleaning', 'short' => 'End-of-Tenancy', 'note' => 'Move-in/move-out cleaning to landlord and letting-agent standard.', 'price' => 'R1,200', 'unit' => 'property'],
        ['value' => 'post-construction', 'name' => 'Post Construction Cleaning', 'short' => 'Post Construction', 'note' => 'We remove what the builders left behind, to a move-in ready standard.', 'price' => 'R1,800', 'unit' => 'project'],
    ];
    try {
        $liveServices = \App\Support\Services::list();
        $heroServices = array_map(function (array $option) use ($liveServices) {
            $live = $liveServices[$option['value']] ?? null;

            return $live ? [
                'value' => $option['value'],
                'name' => $live['name'],
                'short' => $option['short'],
                'note' => $live['tagline'],
                'price' => 'R'.number_format($live['base_price']),
                'unit' => $live['unit_label'],
            ] : $option;
        }, $heroServices);
    } catch (\Throwable $e) {
        // keep the defaults above
    }
@endphp
<section class="relative isolate flex items-end lg:items-center min-h-[100svh] overflow-hidden bg-[#040f1f] pt-28 pb-10 lg:pt-32 lg:pb-14">

    {{-- Photograph. Object position keeps the cleaner in the right half, clear of the headline. --}}
    <img src="/images/works/hero/hero2_cleaning-1280.avif"
         srcset="/images/works/hero/hero2_cleaning-828.avif 828w, /images/works/hero/hero2_cleaning-1280.avif 1280w, /images/works/hero/hero2_cleaning.avif 1680w"
         sizes="100vw"
         width="1680" height="1020"
         fetchpriority="high" decoding="async"
         alt="SpringKleaners cleaner vacuuming a carpet"
         class="absolute inset-0 -z-30 h-full w-full object-cover object-[62%_center]">

    {{-- The film the squeegee wipes off (CSS only, see .hero-film in app.css) --}}
    <div class="hero-film -z-20" aria-hidden="true"><div class="hero-film__panel"></div></div>

    {{-- Scrim: guarantees headline contrast, stronger on mobile where text sits over the photo --}}
    <div class="absolute inset-0 -z-10 bg-[linear-gradient(to_top,rgba(4,15,31,0.96)_0%,rgba(4,15,31,0.82)_55%,rgba(4,15,31,0.55)_100%)] lg:bg-[linear-gradient(95deg,rgba(4,15,31,0.97)_0%,rgba(4,15,31,0.86)_34%,rgba(4,15,31,0.3)_66%,rgba(4,15,31,0.1)_100%)]" aria-hidden="true"></div>

    {{-- Keeps the flat header legible over the bright window and gloves --}}
    <div class="absolute inset-x-0 top-0 -z-10 h-44 bg-gradient-to-b from-[#040f1f]/85 via-[#040f1f]/45 to-transparent" aria-hidden="true"></div>

    <div class="section-wrap w-full">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 xl:gap-16 items-end">

            <div class="lg:col-span-7 lg:pb-3">
                <p id="hero-badge" class="hero-settle mb-7 flex items-center gap-3 text-[14px] font-medium text-white/80">
                    <svg class="h-4 w-[88px] flex-shrink-0 text-[#f6e304]" viewBox="0 0 88 16" fill="currentColor" role="img" aria-label="Rated 4.9 out of 5">
                        <defs><path id="hero-star" d="M8 .6l2.2 4.9 5.3.5-4 3.6 1.2 5.3L8 12.2 3.3 14.9l1.2-5.3-4-3.6 5.3-.5z"/></defs>
                        <use href="#hero-star"/><use href="#hero-star" x="18"/><use href="#hero-star" x="36"/><use href="#hero-star" x="54"/><use href="#hero-star" x="72"/>
                    </svg>
                    4.9 from our Google reviews
                </p>

                <h1 id="hero-headline" class="font-display text-white text-[34px] sm:text-[48px] xl:text-[46px] leading-[1.08] mb-8">
                    <span class="hero-line"><span>Cape Town's Northern Suburbs,</span></span>
                    <span class="hero-line"><span>cleaned properly.</span></span>
                </h1>

                <p id="hero-sub" class="hero-settle max-w-[34rem] text-[17px] leading-relaxed text-white/75 mb-9">
                    Deep cleaning, end-of-tenancy and post-construction cleaning for homes and businesses, with your price before you book.
                </p>

                <div class="hero-settle flex flex-wrap items-center gap-x-6 gap-y-3 mb-10 lg:hidden">
                    <x-arrow-button href="#hero-form">Get my instant quote</x-arrow-button>
                    <a href="#services" class="link-arrow text-[15px] font-semibold text-white underline decoration-white/40 underline-offset-4 hover:decoration-[#f6e304]">See our services<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 17L17 7M8 7h9v9"/></svg></a>
                </div>

                <x-trust-badges theme="ledger" id="hero-trust" class="hero-settle" />
            </div>

            <div class="lg:col-span-5" id="hero-form">
                <div x-data="{
                    suburbs: [
                        'Milnerton', 'Sunningdale', 'Blouberg', 'Parklands', 'Century City',
                        'Table View', 'Big Bay', 'Bloubergstrand', 'West Beach', 'Monte Vista',
                        'Edgemead', 'Bothasig', 'Richwood', 'Burgundy Estate', 'Flamingo Vlei',
                        'Sandown', 'Sunset Beach', 'Parklands North', 'Waves Edge', 'Montague Gardens',
                        'Blouberg Rise', 'Summer Greens', 'Rugby', 'Paarden Eiland', 'Marconi Beam',
                        'Dunoon', 'Joe Slovo Park', 'Penhill', 'Kerria', 'Ravensmead',
                        'Sea Point', 'Green Point'
                    ],
                    suburbQuery: '',
                    filteredSuburbs: [],
                    showSuggestions: false,
                    selectedSuburb: '',
                    locationStatus: null,
                    serviceError: false,
                    errors: { name: false, phone: false },
                    form: {
                        name: '',
                        phone: '',
                        email: '',
                        service: '',
                        propertyType: 'Residential'
                    },
                    waitlistEmail: '',
                    filterSuburbs() {
                        if (this.suburbQuery.length < 1) {
                            this.filteredSuburbs = [];
                            this.showSuggestions = false;
                            return;
                        }
                        this.filteredSuburbs = this.suburbs.filter(s =>
                            s.toLowerCase().includes(this.suburbQuery.toLowerCase())
                        );
                        this.showSuggestions = this.filteredSuburbs.length > 0;
                        this.locationStatus = null;
                    },
                    selectSuburb(suburb) {
                        this.selectedSuburb = suburb;
                        this.suburbQuery = suburb;
                        this.showSuggestions = false;
                        this.locationStatus = 'valid';
                    },
                    checkLocation() {
                        this.showSuggestions = false;
                        if (!this.suburbQuery.trim()) return;
                        const match = this.suburbs.find(s =>
                            s.toLowerCase() === this.suburbQuery.toLowerCase()
                        );
                        if (match) {
                            this.selectedSuburb = match;
                            this.locationStatus = 'valid';
                        } else {
                            this.locationStatus = 'invalid';
                        }
                    },
                    submitForm() {
                        this.errors.name = !this.form.name.trim();
                        this.errors.phone = !this.form.phone.trim();
                        this.serviceError = !this.form.service;
                        if (!this.suburbQuery.trim()) {
                            this.locationStatus = 'required';
                        } else if (this.locationStatus !== 'valid') {
                            this.checkLocation();
                        }
                        if (this.errors.name || this.errors.phone || this.serviceError || this.locationStatus !== 'valid') {
                            return;
                        }
                        const params = new URLSearchParams({
                            service: this.form.service,
                            name: this.form.name,
                            phone: this.form.phone,
                            email: this.form.email,
                            suburb: this.selectedSuburb,
                        });
                        window.location.href = '{{ route('booking.show') }}?' + params.toString();
                    }
                }" class="bg-[#f6f8fb] rounded-xl p-6 sm:p-7 shadow-[0_40px_90px_-30px_rgba(0,0,0,0.75)] ring-1 ring-white/30">

                    <div>
                        <div class="mb-1">
                            <p class="font-display text-[#081d3a] text-[26px] leading-none">Get your instant quote</p>
                            <p class="text-[#5b6779] text-[14px] mt-2">Free, with no obligation.</p>
                        </div>

                        <div class="border-t border-[#081d3a]/10 my-4"></div>

                        <div class="mb-3">
                            <label class="block text-[#081d3a]/80 text-[13px] mb-1.5 font-semibold">Your Suburb <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <input
                                    type="text"
                                    x-model="suburbQuery"
                                    x-on:input="filterSuburbs()"
                                    x-on:blur="setTimeout(() => { showSuggestions = false }, 200)"
                                    x-on:keydown.enter.prevent="checkLocation()"
                                    placeholder="e.g. Milnerton, Parklands..."
                                    class="w-full bg-white border rounded-lg px-4 py-3 text-[#081d3a] text-[14px] placeholder-[#081d3a]/35 focus:border-[#f6e304] focus:outline-none transition-colors"
                                    :class="locationStatus === 'required' ? 'border-red-400' : 'border-[#081d3a]/15'"
                                >
                                <div x-show="showSuggestions"
                                     class="absolute left-0 right-0 top-full mt-1 bg-[#081d3a] border border-white/10 rounded-xl z-50 max-h-48 overflow-y-auto"
                                     style="display:none;">
                                    <template x-for="suburb in filteredSuburbs" :key="suburb">
                                        <div @click="selectSuburb(suburb)"
                                             class="px-4 py-2.5 text-[14px] text-white/80 hover:bg-white/5 hover:text-[#f6e304] cursor-pointer flex items-center gap-2">
                                            <svg class="w-3.5 h-3.5 text-[#f6e304]/60 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span x-text="suburb"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <div x-show="locationStatus === 'required'" class="mt-2 flex items-center gap-1.5 text-red-600 text-[13px]" style="display:none;">
                                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                                <span>Please enter your suburb</span>
                            </div>
                            <div x-show="locationStatus === 'valid'" class="mt-2 flex items-center gap-1.5 text-green-600 text-[13px]" style="display:none;">
                                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                <span>Great! We service <span x-text="selectedSuburb" class="font-semibold"></span></span>
                            </div>
                            <div x-show="locationStatus === 'invalid'" class="mt-2 flex items-center gap-1.5 text-red-600 text-[13px]" style="display:none;">
                                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                                <span>We don't service <span x-text="suburbQuery || 'that area'" class="font-semibold"></span> yet</span>
                            </div>

                            <div x-show="locationStatus !== 'valid' && !showSuggestions" class="mt-3" style="display:none;">
                                <p class="text-[#081d3a]/65 text-[12.5px] mb-2">Popular areas</p>
                                <div class="flex flex-wrap gap-1.5">
                                    <template x-for="suburb in ['Milnerton', 'Blouberg', 'Table View', 'Century City', 'Sandown']" :key="suburb">
                                        <button type="button"
                                                @click="selectSuburb(suburb)"
                                                class="px-2 py-1 bg-[#081d3a]/5 border border-[#081d3a]/10 rounded-full text-[11px] text-[#081d3a]/70 hover:bg-[#f6e304] hover:border-[#f6e304] hover:text-[#081d3a] transition-colors"
                                                x-text="suburb">
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div x-show="locationStatus === 'invalid'"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 translate-y-3"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="mb-3 p-4 bg-[#081d3a]/5 rounded-lg border border-[#081d3a]/10"
                             style="display:none;">
                            <p class="text-[#081d3a] font-semibold text-[14px] mb-1">We're not in <span x-text="suburbQuery || 'your area'" class="font-bold"></span> yet</p>
                            <p class="text-[#5b6779] text-[12px] mb-3">We're expanding! Join our waitlist to be first.</p>
                            <div class="flex gap-2">
                                <input type="email" x-model="waitlistEmail" placeholder="Your email address" class="flex-1 bg-white border border-[#081d3a]/15 rounded-xl px-3 py-2.5 text-[#081d3a] text-[13px] placeholder-[#081d3a]/35 focus:border-[#f6e304] focus:outline-none transition-colors">
                                <button type="button" class="px-4 py-2.5 bg-[#f6e304] text-[#081d3a] font-bold rounded-full text-[13px] hover:bg-yellow-300 transition-colors flex-shrink-0">
                                    Join
                                </button>
                            </div>
                        </div>

                        <div class="space-y-2.5">
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[#081d3a]/80 text-[13px] mb-1.5 font-semibold">Full Name <span class="text-rose-500 normal-case tracking-normal">*</span></label>
                                    <input type="text" x-model="form.name" @input="errors.name = false" placeholder="e.g. Jane Smith" class="w-full bg-white border rounded-lg px-4 py-3 text-[#081d3a] text-[14px] placeholder-[#081d3a]/35 focus:border-[#f6e304] focus:outline-none transition-colors" :class="errors.name ? 'border-red-400' : 'border-[#081d3a]/15'">
                                    <p x-show="errors.name" x-cloak class="text-red-600 text-[11.5px] mt-1">Please enter your name</p>
                                </div>
                                <div>
                                    <label class="block text-[#081d3a]/80 text-[13px] mb-1.5 font-semibold">Phone <span class="text-rose-500 normal-case tracking-normal">*</span></label>
                                    <input type="tel" x-model="form.phone" @input="errors.phone = false" placeholder="082 xxx xxxx" class="w-full bg-white border rounded-lg px-4 py-3 text-[#081d3a] text-[14px] placeholder-[#081d3a]/35 focus:border-[#f6e304] focus:outline-none transition-colors" :class="errors.phone ? 'border-red-400' : 'border-[#081d3a]/15'">
                                    <p x-show="errors.phone" x-cloak class="text-red-600 text-[11.5px] mt-1">Please enter your phone number</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[#081d3a]/80 text-[13px] mb-1.5 font-semibold">Email</label>
                                    <input type="email" x-model="form.email" placeholder="you@email.com" class="w-full bg-white border border-[#081d3a]/15 rounded-lg px-4 py-3 text-[#081d3a] text-[14px] placeholder-[#081d3a]/35 focus:border-[#f6e304] focus:outline-none transition-colors">
                                </div>
                                <div>
                                    <label class="block text-[#081d3a]/80 text-[13px] mb-1.5 font-semibold">Service <span class="text-rose-500 normal-case tracking-normal">*</span></label>
                                    <div class="relative" x-data="{
                                        open: false,
                                        options: @js($heroServices),
                                        get label() { const o = this.options.find(o => o.value === form.service); return o ? o.short : ''; },
                                        pick(o) { form.service = o.value; serviceError = false; this.open = false; this.$nextTick(() => this.$refs.trigger.focus()); }
                                    }" x-on:keydown.escape="open = false" x-on:click.outside="open = false">
                                        <button type="button" id="hero-service" x-ref="trigger" aria-haspopup="listbox" :aria-expanded="open" aria-label="Service"
                                                x-on:click="open = !open"
                                                class="w-full flex items-center justify-between gap-2 bg-white border rounded-lg px-4 py-3 text-left text-[14px] text-[#081d3a] transition-colors cursor-pointer"
                                                :class="serviceError ? 'border-red-400' : (open ? 'border-[#f6e304]' : 'border-[#081d3a]/15')">
                                            <span class="truncate" :class="label ? '' : 'text-[#081d3a]/45'" x-text="label || 'Select one'">Select one</span>
                                            <svg class="h-4 w-4 flex-shrink-0 text-[#081d3a]/55 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                        </button>
                                        <div x-show="open" x-cloak role="listbox" aria-label="Service"
                                             x-transition:enter="transition ease-out duration-150"
                                             x-transition:enter-start="opacity-0 -translate-y-1"
                                             x-transition:enter-end="opacity-100 translate-y-0"
                                             x-transition:leave="transition ease-in duration-100"
                                             x-transition:leave-start="opacity-100"
                                             x-transition:leave-end="opacity-0"
                                             class="absolute right-0 top-full z-50 mt-2 w-[19rem] max-w-[calc(100vw-3rem)] rounded-xl bg-white p-1.5 shadow-[0_24px_60px_-16px_rgba(8,29,58,0.45)] ring-1 ring-[#081d3a]/10"
                                             style="display:none;">
                                            <template x-for="o in options" :key="o.value">
                                                <button type="button" role="option" :aria-selected="form.service === o.value" x-on:click="pick(o)"
                                                        class="flex w-full items-start justify-between gap-4 rounded-lg px-3 py-2.5 text-left transition-colors hover:bg-[#081d3a]/[0.05] focus-visible:bg-[#081d3a]/[0.05] focus-visible:outline-none"
                                                        :class="form.service === o.value ? 'bg-[#081d3a]/[0.06]' : ''">
                                                    <span class="min-w-0">
                                                        <span class="flex items-center gap-1.5 text-[14px] font-semibold text-[#081d3a]">
                                                            <span x-text="o.name"></span>
                                                            <svg x-show="form.service === o.value" class="h-3.5 w-3.5 text-[#081d3a]" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                        </span>
                                                        <span class="mt-0.5 line-clamp-2 text-[12.5px] leading-snug text-[#5b6779]" x-text="o.note"></span>
                                                    </span>
                                                    <span class="flex-shrink-0 text-right leading-tight">
                                                        <span class="block text-[11px] text-[#5b6779]">From</span>
                                                        <span class="block text-[13px] font-bold text-[#081d3a]" x-text="o.price"></span>
                                                        <span class="block text-[11px] text-[#5b6779]" x-text="'/ ' + o.unit"></span>
                                                    </span>
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                    <p x-show="serviceError" x-cloak class="text-red-600 text-[11.5px] mt-1">Please choose a service</p>
                                </div>
                            </div>
                            <div class="flex gap-1 rounded-full bg-[#081d3a]/[0.07] p-1" role="group" aria-label="Property type">
                                <button type="button"
                                        @click="form.propertyType = 'Residential'"
                                        :aria-pressed="form.propertyType === 'Residential'"
                                        :class="form.propertyType === 'Residential' ? 'bg-[#081d3a] text-white shadow' : 'text-[#081d3a]/70 hover:text-[#081d3a]'"
                                        class="flex-1 px-4 py-2.5 rounded-full text-[14px] font-semibold transition-colors">
                                    Residential
                                </button>
                                <button type="button"
                                        @click="form.propertyType = 'Commercial'"
                                        :aria-pressed="form.propertyType === 'Commercial'"
                                        :class="form.propertyType === 'Commercial' ? 'bg-[#081d3a] text-white shadow' : 'text-[#081d3a]/70 hover:text-[#081d3a]'"
                                        class="flex-1 px-4 py-2.5 rounded-full text-[14px] font-semibold transition-colors">
                                    Commercial
                                </button>
                            </div>
                            <p x-show="errors.name || errors.phone || serviceError || locationStatus === 'required'" x-cloak class="text-red-500 text-[12px] -mt-1">Please fill in the highlighted fields above to continue.</p>
                            <x-arrow-button block x-on:click="submitForm()">Get my instant quote</x-arrow-button>
                            <p class="text-center text-[#5b6779] text-[12.5px]">Free inspection. No commitment.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
