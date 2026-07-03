<section id="services" class="bg-white section-py">
    <div class="section-wrap">

        {{-- Header: label left, headline right --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 mb-16 lg:mb-24 wow fadeInUp" data-wow-duration="0.7s">
            <div class="lg:col-span-3 flex flex-col justify-start pt-2">
                <div class="w-8 h-[2px] bg-[#f6e304] mb-3"></div>
                <span class="text-[#081d3a] text-[11px] font-semibold uppercase tracking-[0.18em]">Our Services</span>
            </div>
            <div class="lg:col-span-9">
                <h2 class="text-[48px] sm:text-[60px] lg:text-[72px] xl:text-[82px] font-semibold text-[#081d3a] leading-[1.05] tracking-[-0.02em] mb-6">
                    Discover our services<br>and how we do it better.
                </h2>
                <p class="text-[#647082] text-[15px] leading-relaxed max-w-xl font-normal">
                    We help homes and businesses across Cape Town's Northern Suburbs stay spotlessly clean — with professional service always tailored to what you need.
                </p>
            </div>
        </div>

        {{-- Accordion: hover to open, leave section to close --}}
        <div x-data="{ active: 0 }" @mouseleave="active = null">

            {{-- ── 01: Deep Cleaning ── --}}
            <div @mouseenter="active = 0">

                {{-- Collapsed row --}}
                <div x-show="active !== 0"
                     @click="active = 0"
                     class="flex items-center gap-6 lg:gap-10 py-5 lg:py-6 cursor-pointer group border-b border-[#081d3a]/10 transition-all">
                    <span class="text-[#081d3a]/25 font-semibold text-[13px] tracking-widest w-8 flex-shrink-0">01</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-[#081d3a] font-bold uppercase tracking-wider text-[14px] sm:text-[15px]">Deep Cleaning</p>
                        <p class="text-[#647082] text-[13px] mt-0.5">for homes and spaces that deserve better</p>
                    </div>
                    <div class="w-9 h-9 rounded-full border border-[#081d3a]/20 flex items-center justify-center flex-shrink-0 group-hover:bg-[#081d3a] group-hover:border-[#081d3a] transition-all">
                        <svg class="w-3.5 h-3.5 text-[#081d3a] group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17L17 7M17 7H7M17 7v10"/>
                        </svg>
                    </div>
                </div>

                {{-- Expanded card --}}
                <div x-show="active === 0"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     class="bg-[#f0f0eb] rounded-2xl overflow-hidden">
                    <div class="flex flex-col sm:flex-row items-stretch">

                        <div class="sm:w-[220px] lg:w-[260px] flex-shrink-0 flex flex-col">
                            <div class="px-5 pt-5 pb-0">
                                <span class="text-[#081d3a]/30 font-semibold text-[12px] tracking-widest">01</span>
                            </div>
                            <div class="flex-1 m-4 mt-2 rounded-xl overflow-hidden" style="height:155px">
                                <img src="/images/works/services/deep_cleaning.avif" alt="Deep cleaning service" class="w-full h-full object-cover">
                            </div>
                        </div>

                        <div class="flex-1 px-5 sm:px-6 py-5 sm:py-6 flex flex-col justify-center">
                            <p class="text-[#081d3a] font-bold uppercase tracking-wider text-[13px]">Deep Cleaning</p>
                            <p class="text-[#647082] text-[13px] mt-0.5 mb-4">for homes and spaces that deserve better</p>
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-[#081d3a] font-bold leading-none" style="font-size:32px">4 HRS</span>
                                <span class="relative -top-2">
                                    <svg class="w-3.5 h-3.5 text-[#081d3a]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </span>
                                <span class="text-[#647082] text-[13px]">average time for a full deep clean</span>
                            </div>
                            <p class="text-[#647082] text-[13px] leading-relaxed max-w-xs">
                                Get a refreshed, spotlessly clean home without lifting a finger.
                            </p>
                        </div>

                        <div class="flex flex-col items-center sm:items-end justify-center gap-2 px-5 sm:px-7 pb-5 sm:pb-0 flex-shrink-0">
                            <span class="text-[#647082] text-[11px] whitespace-nowrap">From <span class="text-[#081d3a] font-bold">R1,200</span> / visit</span>
                            <a href="{{ route('booking.show', ['service' => 'deep-cleaning']) }}"
                               class="inline-flex items-center gap-2 bg-[#081d3a] text-[#f6e304] font-semibold px-5 py-3 rounded-full hover:bg-[#0d2a4a] active:scale-95 transition-all text-[13px] whitespace-nowrap">
                                Book This Service
                            </a>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ── 02: End-of-Tenancy ── --}}
            <div @mouseenter="active = 1">

                <div x-show="active !== 1"
                     @click="active = 1"
                     class="flex items-center gap-6 lg:gap-10 py-5 lg:py-6 cursor-pointer group border-b border-[#081d3a]/10 transition-all">
                    <span class="text-[#081d3a]/25 font-semibold text-[13px] tracking-widest w-8 flex-shrink-0">02</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-[#081d3a] font-bold uppercase tracking-wider text-[14px] sm:text-[15px]">End-of-Tenancy</p>
                        <p class="text-[#647082] text-[13px] mt-0.5">for tenants, landlords &amp; letting agents</p>
                    </div>
                    <div class="w-9 h-9 rounded-full border border-[#081d3a]/20 flex items-center justify-center flex-shrink-0 group-hover:bg-[#081d3a] group-hover:border-[#081d3a] transition-all">
                        <svg class="w-3.5 h-3.5 text-[#081d3a] group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17L17 7M17 7H7M17 7v10"/>
                        </svg>
                    </div>
                </div>

                <div x-show="active === 1"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     class="bg-[#f0f0eb] rounded-2xl overflow-hidden">
                    <div class="flex flex-col sm:flex-row items-stretch">

                        <div class="sm:w-[220px] lg:w-[260px] flex-shrink-0 flex flex-col">
                            <div class="px-5 pt-5 pb-0">
                                <span class="text-[#081d3a]/30 font-semibold text-[12px] tracking-widest">02</span>
                            </div>
                            <div class="flex-1 m-4 mt-2 rounded-xl overflow-hidden" style="height:155px">
                                <img src="/images/works/services/end_of_tenancy.avif" alt="End-of-tenancy cleaning" class="w-full h-full object-cover">
                            </div>
                        </div>

                        <div class="flex-1 px-5 sm:px-6 py-5 sm:py-6 flex flex-col justify-center">
                            <p class="text-[#081d3a] font-bold uppercase tracking-wider text-[13px]">End-of-Tenancy</p>
                            <p class="text-[#647082] text-[13px] mt-0.5 mb-4">for tenants, landlords &amp; letting agents</p>
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-[#081d3a] font-bold leading-none" style="font-size:32px">98%</span>
                                <span class="relative -top-2">
                                    <svg class="w-3.5 h-3.5 text-[#081d3a]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                    </svg>
                                </span>
                                <span class="text-[#647082] text-[13px]">client satisfaction rate</span>
                            </div>
                            <p class="text-[#647082] text-[13px] leading-relaxed max-w-xs">
                                Cleaned to landlord standards — helping tenants protect their full deposit.
                            </p>
                        </div>

                        <div class="flex flex-col items-center sm:items-end justify-center gap-2 px-5 sm:px-7 pb-5 sm:pb-0 flex-shrink-0">
                            <span class="text-[#647082] text-[11px] whitespace-nowrap">From <span class="text-[#081d3a] font-bold">R1,200</span> / property</span>
                            <a href="{{ route('booking.show', ['service' => 'end-of-tenancy']) }}"
                               class="inline-flex items-center gap-2 bg-[#081d3a] text-[#f6e304] font-semibold px-5 py-3 rounded-full hover:bg-[#0d2a4a] active:scale-95 transition-all text-[13px] whitespace-nowrap">
                                Book This Service
                            </a>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ── 03: Post Construction ── --}}
            <div @mouseenter="active = 2">

                <div x-show="active !== 2"
                     @click="active = 2"
                     class="flex items-center gap-6 lg:gap-10 py-5 lg:py-6 cursor-pointer group border-b border-[#081d3a]/10 transition-all">
                    <span class="text-[#081d3a]/25 font-semibold text-[13px] tracking-widest w-8 flex-shrink-0">03</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-[#081d3a] font-bold uppercase tracking-wider text-[14px] sm:text-[15px]">Post Construction</p>
                        <p class="text-[#647082] text-[13px] mt-0.5">for builders, contractors &amp; developers</p>
                    </div>
                    <div class="w-9 h-9 rounded-full border border-[#081d3a]/20 flex items-center justify-center flex-shrink-0 group-hover:bg-[#081d3a] group-hover:border-[#081d3a] transition-all">
                        <svg class="w-3.5 h-3.5 text-[#081d3a] group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17L17 7M17 7H7M17 7v10"/>
                        </svg>
                    </div>
                </div>

                <div x-show="active === 2"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     class="bg-[#f0f0eb] rounded-2xl overflow-hidden">
                    <div class="flex flex-col sm:flex-row items-stretch">

                        <div class="sm:w-[220px] lg:w-[260px] flex-shrink-0 flex flex-col">
                            <div class="px-5 pt-5 pb-0">
                                <span class="text-[#081d3a]/30 font-semibold text-[12px] tracking-widest">03</span>
                            </div>
                            <div class="flex-1 m-4 mt-2 rounded-xl overflow-hidden" style="height:155px">
                                <img src="/images/works/services/post_construction.png" alt="Post construction cleaning" class="w-full h-full object-cover">
                            </div>
                        </div>

                        <div class="flex-1 px-5 sm:px-6 py-5 sm:py-6 flex flex-col justify-center">
                            <p class="text-[#081d3a] font-bold uppercase tracking-wider text-[13px]">Post Construction</p>
                            <p class="text-[#647082] text-[13px] mt-0.5 mb-4">for builders, contractors &amp; developers</p>
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-[#081d3a] font-bold leading-none" style="font-size:32px">90%</span>
                                <span class="relative -top-2">
                                    <svg class="w-3.5 h-3.5 text-[#081d3a]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                    </svg>
                                </span>
                                <span class="text-[#647082] text-[13px]">clients rebook after their first clean</span>
                            </div>
                            <p class="text-[#647082] text-[13px] leading-relaxed max-w-xs">
                                We remove what the builders left behind — to a move-in ready standard.
                            </p>
                        </div>

                        <div class="flex flex-col items-center sm:items-end justify-center gap-2 px-5 sm:px-7 pb-5 sm:pb-0 flex-shrink-0">
                            <span class="text-[#647082] text-[11px] whitespace-nowrap">From <span class="text-[#081d3a] font-bold">R1,800</span> / project</span>
                            <a href="{{ route('booking.show', ['service' => 'post-construction']) }}"
                               class="inline-flex items-center gap-2 bg-[#081d3a] text-[#f6e304] font-semibold px-5 py-3 rounded-full hover:bg-[#0d2a4a] active:scale-95 transition-all text-[13px] whitespace-nowrap">
                                Book This Service
                            </a>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
