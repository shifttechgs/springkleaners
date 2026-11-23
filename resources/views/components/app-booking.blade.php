{{-- Mobile app booking flow, shown in the booking sheet. Same logic as the hero card (quoteFlow), laid out the way
     native apps do it: one question per step, tap first (the keyboard only comes up for contact details), steps
     slide in, and the live price with the main button stays pinned at the bottom. Fills its parent's height.
     Opened with the window event `open-quote` ({ service, suburb }); asks its parent to close with `close-sheet`. --}}
@php $quoteFlow = \App\Support\QuoteFlow::config('app'); @endphp
@include('partials.quote-flow-script')

@push('styles')
<style>
    .qf-in-f { animation: qf-in-f 0.32s cubic-bezier(0.22, 1, 0.36, 1); }
    .qf-in-b { animation: qf-in-b 0.32s cubic-bezier(0.22, 1, 0.36, 1); }
    @keyframes qf-in-f { from { opacity: 0; transform: translateX(28px); } to { opacity: 1; transform: none; } }
    @keyframes qf-in-b { from { opacity: 0; transform: translateX(-28px); } to { opacity: 1; transform: none; } }
    @media (prefers-reduced-motion: reduce) { .qf-in-f, .qf-in-b { animation: none; } }
</style>
@endpush

@if (empty($quoteFlow['services']))
    <div class="p-6 text-center">
        <p class="font-display text-[24px] text-[#081d3a]">Book a clean</p>
        <a href="{{ route('booking.show') }}" class="mt-4 inline-block rounded-full bg-[#f6e304] px-6 py-3 font-bold text-[#081d3a]">Open the booking form</a>
    </div>
@else
<div x-data="quoteFlow(@js($quoteFlow))" x-on:open-quote.window="openWith($event.detail || {})"
     x-on:focusin="start()" x-on:pointerdown="start()" class="flex h-full min-h-0 flex-col">

    {{-- Top: back, progress, close --}}
    <div class="flex flex-shrink-0 items-center gap-3 px-4 pb-3">
        <button type="button" x-on:click="back()" x-show="step > 1 && !done" aria-label="Back"
                class="press -ml-2 grid h-10 w-10 place-items-center rounded-full text-[#081d3a] hover:bg-[#081d3a]/[0.06]">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <div x-show="!done" class="flex flex-1 gap-1" aria-hidden="true">
            <template x-for="n in stepCount" :key="n">
                <span class="h-1 flex-1 rounded-full transition-colors duration-300" :class="n <= step ? 'bg-[#081d3a]' : 'bg-[#081d3a]/12'"></span>
            </template>
        </div>
        <div x-show="done" class="flex-1"></div>
        <button type="button" x-on:click="$dispatch('close-sheet')" aria-label="Close"
                class="press -mr-2 grid h-10 w-10 place-items-center rounded-full text-[#081d3a]/70 hover:bg-[#081d3a]/[0.06]">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/></svg>
        </button>
    </div>

    {{-- Steps (scroll inside the sheet) --}}
    <div class="min-h-0 flex-1 overflow-y-auto overscroll-contain px-4 pb-6" data-sheet-scroll>

        {{-- 1. Service: tap a tile, moves on by itself --}}
        <section x-show="!done && stepKey === 'service'" x-ref="step1" :class="dir > 0 ? 'qf-in-f' : 'qf-in-b'">
            <h2 class="font-display text-[26px] leading-tight text-[#081d3a]">What needs cleaning?</h2>
            <div class="mt-4 grid grid-cols-2 gap-3">
                <template x-for="(svc, key) in services" :key="key">
                    <button type="button" x-on:click="chooseService(key)" :aria-pressed="serviceKey === key"
                            class="press overflow-hidden rounded-2xl border-2 bg-white text-left transition-colors"
                            :class="serviceKey === key ? 'border-[#081d3a]' : 'border-transparent shadow-[0_1px_0_rgba(8,29,58,0.08)]'">
                        <img :src="svc.img" :alt="svc.alt" loading="lazy" class="aspect-[4/3] w-full object-cover">
                        <span class="block px-3 pb-3 pt-2.5">
                            <span class="block text-[15px] font-bold leading-tight text-[#081d3a]" x-text="svc.short"></span>
                            <span class="mt-0.5 block text-[12.5px] text-[#5b6779]" x-text="'from ' + fromPrice(key)"></span>
                        </span>
                    </button>
                </template>
            </div>
            <a href="{{ route('services.show', 'office-commercial-cleaning') }}" class="mt-4 inline-block text-[13px] text-[#5b6779] underline decoration-[#081d3a]/20 underline-offset-2">Office or commercial space? Get a custom quote</a>
        </section>

        {{-- 2. Size: the price in the bar below moves as they tap --}}
        <section x-show="!done && stepKey === 'size'" x-cloak x-ref="step2" :class="dir > 0 ? 'qf-in-f' : 'qf-in-b'">
            <p class="text-[13px] font-semibold text-[#5b6779]" x-text="service ? service.name : ''"></p>
            <h2 class="font-display text-[26px] leading-tight text-[#081d3a]">Tell us about your place</h2>
            <div class="mt-4 flex gap-1 rounded-full bg-[#081d3a]/[0.07] p-1" role="group" aria-label="Property type">
                <template x-for="t in ['House', 'Apartment / Flat', 'Townhouse']" :key="t">
                    <button type="button" x-on:click="propertyType = t" :aria-pressed="propertyType === t"
                            :class="propertyType === t ? 'bg-[#081d3a] text-white shadow' : 'text-[#081d3a]/70'"
                            class="press flex-1 rounded-full px-2 py-2.5 text-[14px] font-semibold transition-colors" x-text="t === 'Apartment / Flat' ? 'Apartment' : t"></button>
                </template>
            </div>
            <div class="mt-3 divide-y divide-[#081d3a]/10 rounded-2xl bg-white">
                <template x-for="row in [
                    { key: 'bedrooms', label: 'Bedrooms', min: 1, max: 5 },
                    { key: 'bathrooms', label: 'Bathrooms', min: 1, max: 4 },
                    { key: 'extraRooms', label: 'Extra rooms', hint: 'Living, dining, study', min: 0, max: 4 },
                ]" :key="row.key">
                    <div class="flex items-center justify-between gap-3 px-4 py-3">
                        <div>
                            <p class="text-[15px] font-semibold text-[#081d3a]" x-text="row.label"></p>
                            <p x-show="row.hint" class="text-[12px] text-[#5b6779]" x-text="row.hint"></p>
                        </div>
                        <div class="flex items-center gap-3">
                            <button type="button" x-on:click="$data[row.key] = Math.max(row.min, $data[row.key] - 1)" :disabled="$data[row.key] <= row.min" :aria-label="'Fewer ' + row.label.toLowerCase()"
                                    class="press grid h-11 w-11 place-items-center rounded-full border border-[#081d3a]/15 text-[20px] font-bold text-[#081d3a] disabled:opacity-30">−</button>
                            <span class="w-7 text-center text-[17px] font-bold text-[#081d3a]" x-text="sizeLabel($data[row.key], row.max)" aria-live="polite"></span>
                            <button type="button" x-on:click="$data[row.key] = Math.min(row.max, $data[row.key] + 1)" :disabled="$data[row.key] >= row.max" :aria-label="'More ' + row.label.toLowerCase()"
                                    class="press grid h-11 w-11 place-items-center rounded-full border border-[#081d3a]/15 text-[20px] font-bold text-[#081d3a] disabled:opacity-30">+</button>
                        </div>
                    </div>
                </template>
            </div>
            <p class="mt-3 text-[12.5px] text-[#5b6779]">Your price is confirmed with you before we start.</p>
        </section>

        {{-- 3. Suburb: tap one (typing only narrows the list), moves on by itself --}}
        <section x-show="!done && stepKey === 'where'" x-cloak x-ref="step3" :class="dir > 0 ? 'qf-in-f' : 'qf-in-b'"
                 x-data="{ find: '' }">
            <h2 class="font-display text-[26px] leading-tight text-[#081d3a]">Where's the clean?</h2>
            <div class="mt-4 grid grid-cols-2 gap-2">
                <template x-for="s in popularSuburbs" :key="s">
                    <button type="button" x-on:click="chooseSuburb(s)" :aria-pressed="selectedSuburb === s"
                            class="press rounded-xl border-2 px-3 py-3 text-left text-[15px] font-semibold transition-colors"
                            :class="selectedSuburb === s ? 'border-[#081d3a] bg-white text-[#081d3a]' : 'border-transparent bg-white text-[#081d3a]'" x-text="s"></button>
                </template>
            </div>
            <p class="mb-2 mt-5 text-[12px] font-semibold uppercase tracking-[0.12em] text-[#5b6779]">All areas we clean</p>
            <input type="text" x-model="find" placeholder="Search your suburb" autocomplete="address-level2" enterkeyhint="search"
                   class="w-full rounded-xl border border-[#081d3a]/15 bg-white px-4 py-3 text-[16px] text-[#081d3a] placeholder-[#081d3a]/40 focus:border-[#f6e304] focus:outline-none">
            <div class="mt-2 flex flex-wrap gap-2">
                <template x-for="s in suburbs.filter(x => !popularSuburbs.includes(x) && x.toLowerCase().includes(find.trim().toLowerCase()))" :key="s">
                    <button type="button" x-on:pointerdown.prevent="chooseSuburb(s)" x-on:click="selectedSuburb !== s && chooseSuburb(s)"
                            class="press rounded-full border px-3.5 py-2 text-[14px] transition-colors"
                            :class="selectedSuburb === s ? 'border-[#081d3a] bg-[#081d3a] text-white' : 'border-[#081d3a]/15 bg-white text-[#081d3a]'" x-text="s"></button>
                </template>
            </div>
            <p x-show="find.trim().length > 2 && !suburbs.some(x => x.toLowerCase().includes(find.trim().toLowerCase()))" x-cloak class="mt-3 rounded-xl bg-white p-3 text-[13.5px] text-[#5b6779]">
                We're not in <b class="text-[#081d3a]" x-text="find.trim()"></b> yet. <a href="{{ \App\Support\WhatsApp::link() }}" :href="@js(\App\Support\WhatsApp::base()) + encodeURIComponent('Hi SpringKleaners, do you clean in ' + find.trim() + '? Please let me know when you do.')" target="_blank" rel="noopener noreferrer" class="font-semibold text-[#081d3a] underline">WhatsApp us</a> and we'll tell you when we are.
            </p>
            <p x-show="locationStatus === 'required'" x-cloak class="mt-3 text-[13px] text-red-600">Pick your suburb to carry on.</p>
        </section>

        {{-- 4. Date & time from real availability --}}
        <section x-show="!done && stepKey === 'when'" x-cloak x-ref="step4" :class="dir > 0 ? 'qf-in-f' : 'qf-in-b'">
            <h2 class="font-display text-[26px] leading-tight text-[#081d3a]">When suits you?</h2>
            <p class="mt-1 text-[13px] text-[#5b6779]">We clean on Saturdays and Sundays.</p>
            <div x-show="loadingDates" class="no-bar -mx-4 mt-4 flex gap-2 overflow-hidden px-4" aria-hidden="true">
                <template x-for="n in 4" :key="n"><span class="h-[84px] w-[86px] flex-shrink-0 animate-pulse rounded-2xl bg-[#081d3a]/[0.07]"></span></template>
            </div>
            <div x-show="!loadingDates" class="no-bar -mx-4 mt-4 flex snap-x gap-2 overflow-x-auto scroll-px-4 px-4">
                <template x-for="o in dateOptions" :key="o.iso">
                    <button type="button" x-on:click="pickDate(o)" :disabled="o.left < 1" :aria-pressed="date === o.iso"
                            class="press w-[86px] flex-shrink-0 snap-start rounded-2xl border-2 px-2 py-3 text-center transition-colors disabled:opacity-40"
                            :class="date === o.iso ? 'border-[#081d3a] bg-[#081d3a] text-white' : 'border-transparent bg-white text-[#081d3a]'">
                        <span class="block text-[12px] font-semibold opacity-70" x-text="o.day"></span>
                        <span class="block text-[16px] font-bold leading-tight" x-text="o.date"></span>
                        <span class="mt-1 block text-[11px]" :class="date === o.iso ? 'text-white/75' : (o.left ? 'text-emerald-700' : 'text-rose-600')" x-text="o.closed ? 'Closed' : (o.left ? (o.left === 1 ? '1 slot left' : o.left + ' slots') : 'Full')"></span>
                    </button>
                </template>
            </div>
            <p class="mb-2 mt-5 text-[12px] font-semibold uppercase tracking-[0.12em] text-[#5b6779]">Time</p>
            <div class="grid grid-cols-2 gap-2">
                <template x-for="(s, i) in slotLabels" :key="s">
                    <button type="button" x-on:click="slot = s; errors.when = false" :disabled="!date || slotTaken(s)" :aria-pressed="slot === s"
                            class="press rounded-2xl border-2 px-3 py-3.5 text-left transition-colors disabled:opacity-40"
                            :class="slot === s ? 'border-[#081d3a] bg-[#081d3a] text-white' : 'border-transparent bg-white text-[#081d3a]'">
                        <span class="block text-[15px] font-bold" x-text="i === 0 ? 'Morning' : 'Afternoon'"></span>
                        <span class="block text-[12px] opacity-75" x-text="date && slotTaken(s) ? 'Booked' : s"></span>
                    </button>
                </template>
            </div>
            <p x-show="!date" class="mt-2 text-[12.5px] text-[#5b6779]">Pick a date first.</p>
            <p x-show="errors.when" x-cloak class="mt-2 text-[13px] text-red-600">Pick a date and a time.</p>
        </section>

        {{-- 5. Contact details, consent, summary --}}
        <section x-show="!done && stepKey === 'details'" x-cloak x-ref="step5" :class="dir > 0 ? 'qf-in-f' : 'qf-in-b'" class="relative" :aria-busy="submitting ? 'true' : 'false'">
            <h2 class="font-display text-[26px] leading-tight text-[#081d3a]">Last step: your details</h2>
            <div class="mt-3 rounded-2xl bg-white p-3.5 text-[13.5px]">
                <p class="font-bold text-[#081d3a]" x-text="service ? service.name : ''"></p>
                <p class="text-[#5b6779]"><span x-text="whenLabel"></span> · <span x-text="selectedSuburb"></span></p>
            </div>
            <div class="mt-3 space-y-2.5">
                @foreach ([
                    ['name', 'Full name', 'text', 'name', 'Jane Smith', 'autocapitalize="words" enterkeyhint="next"'],
                    ['phone', 'Phone', 'tel', 'tel', '082 xxx xxxx', 'inputmode="tel" enterkeyhint="next"'],
                    ['email', 'Email', 'email', 'email', 'you@email.com', 'inputmode="email" autocapitalize="off" spellcheck="false" enterkeyhint="next"'],
                    ['address', 'Street address', 'text', 'street-address', 'e.g. 12 Beach Road', 'enterkeyhint="done"'],
                ] as [$field, $label, $type, $auto, $ph, $extra])
                    <label class="block">
                        <span class="mb-1 block text-[13px] font-semibold text-[#081d3a]/80">{{ $label }}</span>
                        <input type="{{ $type }}" x-model="form.{{ $field }}" x-on:input="errors.{{ $field }} = false" autocomplete="{{ $auto }}" placeholder="{{ $ph }}" {!! $extra !!}
                               class="w-full rounded-xl border bg-white px-4 py-3 text-[16px] text-[#081d3a] placeholder-[#081d3a]/35 focus:border-[#f6e304] focus:outline-none"
                               :class="errors.{{ $field }} ? 'border-red-400' : 'border-[#081d3a]/15'">
                    </label>
                @endforeach
            </div>
            <p x-show="errors.name || errors.phone || errors.email || errors.address" x-cloak class="mt-2 text-[13px] text-red-600">Please fill in the highlighted fields.</p>
            <x-photo-consent model="photoConsent" id="app-photo-consent" class="mt-3 rounded-2xl border-transparent" />
            <p class="mt-3 text-[12.5px] text-[#5b6779]">No payment now. We confirm every booking with you on WhatsApp first.</p>
        </section>

        <p x-show="serverError && !done" x-cloak x-text="serverError" class="mt-3 rounded-xl bg-red-50 px-3 py-2 text-[13px] font-semibold text-red-600" role="alert"></p>

        {{-- Done --}}
        <section x-show="done" x-cloak class="qf-in-f pt-6 text-center" role="status" aria-live="polite">
            <div class="mx-auto mb-4 grid h-14 w-14 place-items-center rounded-full bg-emerald-100">
                <svg class="h-7 w-7 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h2 x-ref="title" tabindex="-1" class="font-display text-[26px] leading-tight text-[#081d3a] focus:outline-none">Booking request sent!</h2>
            <p class="mt-2 text-[14px] text-[#5b6779]" x-text="(service ? service.name : '') + ' · ' + whenLabel"></p>
            <p class="mt-1 text-[22px] font-bold text-[#081d3a]" x-text="money(total)"></p>
            <p class="mt-3 text-[13.5px] text-[#5b6779]">We'll confirm on WhatsApp shortly. A confirmation email is on its way to <b class="text-[#081d3a]" x-text="form.email"></b>.</p>
        </section>
    </div>

    {{-- Pinned bar: live price + the one main action --}}
    <div class="tab-safe flex flex-shrink-0 items-center gap-3 border-t border-[#081d3a]/10 bg-white px-4 pt-3" x-show="!(stepKey === 'service' && !service) || done">
        <template x-if="!done">
            <div class="flex w-full items-center gap-3">
                <div class="min-w-0 flex-1" x-show="service">
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-[#5b6779]">Your price</p>
                    <p class="text-[22px] font-extrabold leading-none text-[#081d3a]" x-text="money(total)"></p>
                </div>
                <button type="button" x-show="stepKey !== 'details'" x-on:click="next()"
                        class="press flex-shrink-0 rounded-full bg-[#f6e304] px-7 py-3.5 text-[15px] font-bold text-[#081d3a]">Continue</button>
                <button type="button" x-show="stepKey === 'details'" x-on:click="submit()" :disabled="submitting"
                        class="press flex flex-shrink-0 items-center gap-2 rounded-full bg-[#081d3a] px-6 py-3.5 text-[15px] font-bold text-white disabled:opacity-80">
                    <svg x-show="submitting" x-cloak class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" class="opacity-25"></circle><path d="M22 12a10 10 0 00-10-10" stroke="currentColor" stroke-width="3" stroke-linecap="round"></path></svg>
                    <span x-text="submitting ? 'Booking…' : 'Book now'"></span>
                </button>
            </div>
        </template>
        <template x-if="done">
            <div class="flex w-full gap-2">
                <a href="{{ \App\Support\WhatsApp::link() }}" :href="@js(\App\Support\WhatsApp::base()) + encodeURIComponent('Hi SpringKleaners, I just booked ' + (service ? service.name : 'a clean') + ' for ' + whenLabel + ' in ' + selectedSuburb + '. My name is ' + form.name.trim() + '.')" target="_blank" rel="noopener noreferrer" class="press flex-1 rounded-full bg-[#25d366] py-3.5 text-center text-[15px] font-bold text-[#081d3a]">WhatsApp us</a>
                <button type="button" x-on:click="$dispatch('close-sheet')" class="press flex-1 rounded-full border border-[#081d3a]/15 py-3.5 text-[15px] font-bold text-[#081d3a]">Done</button>
            </div>
        </template>
    </div>
</div>
@endif
