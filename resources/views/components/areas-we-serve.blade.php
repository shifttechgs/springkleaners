@php
    // Page-backed areas are grouped the same way as the /areas hub.
    $locations = collect(config('locations'));
    $atlantic = ['sea-point', 'green-point'];
    $clusters = [
        'Northern Suburbs & Blaauwberg' => $locations->except($atlantic),
        'Atlantic Seaboard' => $locations->only($atlantic),
    ];
    // name => page url, so the check can link straight to an area page when one exists
    $pageUrls = $locations->mapWithKeys(fn ($l, $slug) => [$l['name'] => route('areas.show', $slug)])->all();
@endphp
<section id="areas" class="bg-[#f8f9fc] section-py">
    <div class="section-wrap">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 xl:gap-16">

            {{-- Answer the one question this section exists for: "do you come to me?" --}}
            <div class="lg:col-span-5">
                <div class="w-8 h-[2px] bg-[#f6e304] mb-3"></div>
                <span class="text-[#081d3a] text-[11px] font-semibold uppercase tracking-[0.18em]">Areas We Serve</span>
                <h2 class="text-4xl lg:text-5xl font-extrabold text-[#081d3a] tracking-tight leading-tight mt-3 mb-5">
                    Do we come to you?
                </h2>
                <p class="text-[#5b6779] text-lg mb-8 max-w-md">Type your suburb and we'll tell you straight away.</p>

                <div x-data="{
                        q: '',
                        all: @js(config('served_suburbs')),
                        pages: @js($pageUrls),
                        quote: @js(route('booking.show')),
                        whatsapp: 'https://wa.me/27815274711?text=',
                        get query() { return this.q.trim(); },
                        get exact() { return this.all.find(s => s.toLowerCase() === this.query.toLowerCase()) || null; },
                        get matches() {
                            if (this.query.length < 2) return [];
                            const t = this.query.toLowerCase();
                            return this.all.filter(s => s.toLowerCase().includes(t)).slice(0, 6);
                        },
                        get state() {
                            if (!this.query) return 'idle';
                            if (this.exact) return 'yes';
                            if (this.matches.length) return 'typing';
                            return this.query.length >= 3 ? 'no' : 'idle';
                        },
                        get quoteUrl() { return this.quote + '?suburb=' + encodeURIComponent(this.exact || this.query); },
                        get askUrl() { return this.whatsapp + encodeURIComponent('Hi SpringKleaners! Do you service ' + this.query + '?'); }
                    }">
                    <label for="suburb-check" class="mb-2 block text-[13px] font-semibold text-[#081d3a]/80">Your suburb</label>
                    <input id="suburb-check" type="text" x-model="q" autocomplete="off" placeholder="e.g. Sandown, Century City&hellip;"
                           class="w-full rounded-full border border-[#081d3a]/15 bg-white px-6 py-4 text-[16px] text-[#081d3a] placeholder-[#081d3a]/40 transition-colors focus:border-[#f6e304] focus:outline-none">

                    <div class="mt-4 min-h-[5.5rem]" aria-live="polite">
                        <p x-show="state === 'idle'" class="text-[14px] text-[#5b6779]">Try Milnerton, Sandown or Century City.</p>

                        <div x-show="state === 'typing'" x-cloak class="flex flex-wrap gap-2" style="display:none;">
                            <template x-for="s in matches" :key="s">
                                <button type="button" x-on:click="q = s" x-text="s"
                                        class="rounded-full border border-[#081d3a]/12 bg-white px-4 py-2 text-[13.5px] font-medium text-[#081d3a] transition-colors hover:border-[#f6e304] hover:bg-[#f6e304]"></button>
                            </template>
                        </div>

                        <div x-show="state === 'yes'" x-cloak style="display:none;">
                            <p class="flex items-center gap-2 text-[16px] font-semibold text-emerald-800">
                                <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                <span>Yes, we clean in <span x-text="exact"></span>.</span>
                            </p>
                            <div class="mt-4 flex flex-wrap items-center gap-x-6 gap-y-3">
                                <x-arrow-button href="#" x-bind:href="quoteUrl">Get My Instant Quote</x-arrow-button>
                                <a x-show="pages[exact]" x-bind:href="pages[exact]" class="link-arrow text-[15px] font-semibold text-[#081d3a] underline decoration-[#081d3a]/30 underline-offset-4 hover:decoration-[#081d3a]">
                                    See cleaning in <span x-text="exact"></span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 17L17 7M8 7h9v9"/></svg>
                                </a>
                            </div>
                        </div>

                        <div x-show="state === 'no'" x-cloak style="display:none;">
                            <p class="text-[16px] font-semibold text-[#081d3a]">We don't cover <span x-text="query"></span> yet.</p>
                            <p class="mt-1 text-[14px] text-[#5b6779]">Message us and we'll tell you if we can make it work.</p>
                            <a href="#" x-bind:href="askUrl" target="_blank" rel="noopener noreferrer"
                               class="link-arrow mt-3 text-[15px] font-semibold text-[#081d3a] underline decoration-[#081d3a]/30 underline-offset-4 hover:decoration-[#081d3a]">
                                Ask on WhatsApp<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 17L17 7M8 7h9v9"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Every area with its own page, as plain typography --}}
            <div class="lg:col-span-7 lg:pt-3">
                <div class="space-y-10">
                    @foreach ($clusters as $label => $group)
                        <div>
                            <h3 class="border-b border-[#081d3a]/12 pb-3 text-[14px] font-semibold text-[#081d3a]">{{ $label }}</h3>
                            <ul class="mt-4 columns-2 sm:columns-3 gap-x-8">
                                @foreach ($group as $slug => $area)
                                    <li class="break-inside-avoid">
                                        <a href="{{ route('areas.show', $slug) }}"
                                           class="inline-block py-1.5 text-[16px] font-medium text-[#081d3a] underline decoration-transparent underline-offset-4 transition-colors hover:decoration-[#f6e304]">{{ $area['name'] }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>

                <a href="{{ route('areas.index') }}" class="link-arrow mt-10 text-[15px] font-semibold text-[#081d3a] underline decoration-[#081d3a]/30 underline-offset-4 hover:decoration-[#081d3a]">
                    How we work in each area<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 17L17 7M8 7h9v9"/></svg>
                </a>
            </div>

        </div>
    </div>
</section>
