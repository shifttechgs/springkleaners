<section class="bg-[#f7f7f5] section-py" id="faq">
    <div class="section-wrap">

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-10 lg:gap-14 items-start">

            {{-- Left: Intro + Image (2 cols) --}}
            <div class="lg:col-span-2 lg:sticky lg:top-28 wow fadeInUp" data-wow-duration="0.7s">
                <div class="w-8 h-[2px] bg-[#f6e304] mb-3"></div>
                <span class="text-[#081d3a] text-[11px] font-semibold uppercase tracking-[0.18em]">FAQ</span>
                <h2 class="text-3xl lg:text-[36px] font-extrabold text-[#081d3a] tracking-tight leading-[1.12] mt-3 mb-4">
                    Questions we get<br>all the time.
                </h2>
                <p class="text-[#647082] text-[13px] leading-relaxed mb-5 max-w-xs">
                    Can't find your answer? WhatsApp us — we reply instantly.
                </p>
                <a href="https://wa.me/27814303023"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="inline-flex items-center gap-2 bg-[#081d3a] text-white rounded-full px-5 py-2.5 text-[12px] font-semibold hover:bg-[#0a2444] transition-colors mb-8">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    Chat on WhatsApp
                </a>
                <div class="rounded-2xl overflow-hidden bg-[#e8e8e4] hidden lg:block" style="max-height:320px">
                    <img src="/images/works/we_clean.avif" alt="Professional cleaner at work" class="w-full h-full object-cover">
                </div>
            </div>

            {{-- Right: Accordion (3 cols) --}}
            <div class="lg:col-span-3 space-y-2.5 wow fadeInUp" data-wow-duration="0.7s" data-wow-delay="0.15s" x-data="{ active: 1 }">

                @php
                $faqs = [
                    ['q' => 'How do you calculate the final price?', 'a' => 'We don\'t charge a fixed rate because every property is different. After you contact us, we arrange a free, no-obligation site inspection. We assess the size, condition, and scope of the clean, then provide a detailed, itemised quote. You approve it before we start — no surprises.'],
                    ['q' => 'Which areas in Cape Town do you service?', 'a' => 'We currently service Cape Town\'s Northern Suburbs, including Milnerton, Sunningdale, Blouberg, Parklands, Century City, Table View, Big Bay, Bloubergstrand, West Beach, Monte Vista, Edgemead, Bothasig, Richwood, Burgundy Estate, Flamingo Vlei, and surrounding areas.'],
                    ['q' => 'Are your cleaning teams insured?', 'a' => 'Yes. SpringKleaners is fully insured. All our team members are covered by public liability insurance, so you\'re protected throughout the cleaning process. We also background-check every team member before they join us.'],
                    ['q' => 'Do you supply your own cleaning equipment?', 'a' => 'Yes. Our teams arrive fully equipped with professional-grade, eco-friendly cleaning products and all necessary equipment. You don\'t need to provide anything.'],
                    ['q' => 'What\'s included in an end-of-tenancy clean?', 'a' => 'Our end-of-tenancy service covers a full deep clean of the entire property to landlord standards — including kitchen appliances, all bathrooms, interior windows, walls, all floors, and inside all cupboards. We also provide a condition checklist.'],
                    ['q' => 'Can I get a same-week booking?', 'a' => 'In most cases, yes. We do our best to accommodate urgent requests. Contact us via WhatsApp for the fastest response and we\'ll check availability immediately.'],
                    ['q' => 'What if I\'m not satisfied with the clean?', 'a' => 'We offer a satisfaction guarantee. If you\'re not happy with any aspect of the clean, let us know within 24 hours and we\'ll return to address it at no additional charge.'],
                ];
                @endphp

                @foreach($faqs as $i => $faq)
                <div class="group rounded-xl transition-all duration-200"
                     :class="active === {{ $i + 1 }} ? 'bg-white shadow-sm ring-1 ring-gray-200' : 'bg-white/60 hover:bg-white ring-1 ring-transparent hover:ring-gray-100'">
                    <button @click="active === {{ $i + 1 }} ? active = null : active = {{ $i + 1 }}"
                            class="w-full flex justify-between items-center px-5 py-4 text-left gap-4">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="text-[#081d3a]/10 text-[13px] font-black tabular-nums flex-shrink-0">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="text-[#081d3a] font-semibold text-[14px] tracking-tight">{{ $faq['q'] }}</span>
                        </div>
                        <span class="w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0 transition-all duration-200"
                              :class="active === {{ $i + 1 }} ? 'bg-[#f6e304] text-[#081d3a] rotate-45' : 'border border-gray-300 text-gray-400'">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12M6 12h12"/>
                            </svg>
                        </span>
                    </button>
                    <div x-show="active === {{ $i + 1 }}"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 max-h-0"
                         x-transition:enter-end="opacity-100 max-h-96"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="px-5 pb-4 pl-[52px]"
                         style="display:none;">
                        <p class="text-[#647082] text-[13px] leading-relaxed">{{ $faq['a'] }}</p>
                    </div>
                </div>
                @endforeach

            </div>

        </div>

    </div>
</section>
