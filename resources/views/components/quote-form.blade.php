@php $quoteFlow = \App\Support\QuoteFlow::config('hero'); @endphp
@include('partials.quote-flow-script')
{{-- The instant quote + booking card (4 steps). Used in the homepage hero and, on the mobile app preview, inside
     the booking sheet. Listens for the window event `open-quote` ({ service }) to preselect a service. --}}
@if (empty($quoteFlow['services']))
    <div class="bg-[#f6f8fb] rounded-xl p-6 sm:p-7 ring-1 ring-white/30">
        <p class="font-display text-[#081d3a] text-[26px] leading-none mb-3">Get your instant quote</p>
        <x-arrow-button block href="{{ route('booking.show') }}">Start my quote</x-arrow-button>
    </div>
@else
{{-- Instant quote + booking in four short steps, right here in the hero: what & where, size (live
     price), when (real availability), contact. It submits to the same endpoint as the full booking
     form, so slot rules, alerts and emails all apply. Everything goes in quote-safe (@js / Js::from). --}}
<div x-data="quoteFlow(@js($quoteFlow))" x-on:focusin="start()" x-on:pointerdown="start()"
   x-on:open-quote.window="openWith($event.detail || {})" class="bg-[#f6f8fb] rounded-xl p-6 sm:p-7 shadow-[0_40px_90px_-30px_rgba(0,0,0,0.75)] ring-1 ring-white/30">

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
                    <input id="hero-suburb" data-suburb-input type="text" x-model="suburbQuery" x-on:input="filterSuburbs()" x-on:blur="suburbBlur()"
                           x-on:keydown.enter.prevent="checkLocation()" autocomplete="address-level2" placeholder="e.g. Milnerton, Parklands..."
                           class="w-full bg-white border rounded-lg px-4 py-3 text-[#081d3a] text-[16px] lg:text-[14px] placeholder-[#081d3a]/35 focus:border-[#f6e304] focus:outline-none transition-colors"
                           :class="locationStatus === 'required' || locationStatus === 'invalid' ? 'border-red-400' : 'border-[#081d3a]/15'">
                    <div x-show="showSuggestions" class="absolute left-0 right-0 top-full mt-1 bg-[#081d3a] border border-white/10 rounded-xl z-50 max-h-48 overflow-y-auto" style="display:none;">
                        <template x-for="s in filteredSuburbs" :key="s">
                            <button type="button" x-on:pointerdown.prevent="pickSuburb(s)" x-on:click="pickSuburb(s)" class="block w-full px-4 py-3 text-left text-[15px] lg:text-[14px] text-white/85 hover:bg-white/5 hover:text-[#f6e304] cursor-pointer" x-text="s"></button>
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
                        <button type="button" x-on:pointerdown.prevent="pickSuburb(s)" x-on:click="pickSuburb(s)" class="px-3 py-1.5 lg:px-2 lg:py-1 bg-[#081d3a]/5 border border-[#081d3a]/10 rounded-full text-[12.5px] lg:text-[11px] text-[#081d3a]/70 hover:bg-[#f6e304] hover:border-[#f6e304] hover:text-[#081d3a] transition-colors" x-text="s"></button>
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
