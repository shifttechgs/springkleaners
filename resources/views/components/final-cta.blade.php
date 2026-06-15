<section class="section-py bg-[#f8f9fc]">
    <div class="section-wrap">
        <div class="relative rounded-3xl overflow-hidden wow fadeIn" data-wow-duration="0.9s" style="min-height: 420px;">

            {{-- Background image --}}
            <img src="/images/works/final_cta_image.webp"
                 alt="SpringKleaners professional cleaning service"
                 class="absolute inset-0 w-full h-full object-cover object-center">

            {{-- Dark overlay: stronger on left, fades right --}}
            <div class="absolute inset-0" style="background: linear-gradient(105deg, rgba(4,15,31,0.82) 0%, rgba(4,15,31,0.60) 50%, rgba(4,15,31,0.30) 100%);"></div>

            {{-- Main content --}}
            <div class="relative z-10 flex flex-col h-full" style="min-height: 420px;">

                {{-- Top area --}}
                <div class="flex-1 grid grid-cols-1 lg:grid-cols-2 gap-8 px-8 sm:px-12 pt-12 pb-8 lg:pt-14 lg:pb-10">

                    {{-- Left: Headline --}}
                    <div class="flex flex-col justify-center">
                        <h2 class="text-white font-extrabold leading-[1.1] tracking-tight"
                            style="font-size: clamp(38px, 5vw, 72px);">
                            Cleaning that<br>works around<br>you
                        </h2>
                        <p class="text-white/60 text-[15px] leading-relaxed mt-5 max-w-xs">
                            Our expert cleaners handle the mess so you can focus on what matters.
                        </p>
                    </div>

                    {{-- Right: Service list --}}
                    <div class="flex flex-col justify-center items-start lg:items-end gap-4 lg:gap-5">
                        <p class="text-white/40 text-[11px] font-bold uppercase tracking-widest mb-1">What we do</p>
                        <p class="text-white font-bold uppercase tracking-widest text-[13px] sm:text-[15px]">Deep Cleaning</p>
                        <p class="text-white font-bold uppercase tracking-widest text-[13px] sm:text-[15px]">End-of-Tenancy Cleaning</p>
                        <p class="text-white font-bold uppercase tracking-widest text-[13px] sm:text-[15px]">Post Construction Cleaning</p>
                        <p class="text-white font-bold uppercase tracking-widest text-[13px] sm:text-[15px]">Free Property Inspection</p>
                    </div>
                </div>

                {{-- Yellow floating card --}}
                <div class="px-4 sm:px-6 pb-4 sm:pb-6">
                    <div class="bg-[#f6e304] rounded-2xl px-6 sm:px-10 py-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <p class="text-[#081d3a] font-black uppercase tracking-tight text-[15px] sm:text-[17px] leading-snug">
                            Got a space in need of a refresh?
                        </p>
                        <a href="/get-my-quote"
                           class="inline-flex items-center gap-3 bg-[#081d3a] text-white font-semibold px-6 py-3.5 rounded-full hover:bg-[#0d2a4a] active:scale-95 transition-all text-[14px] tracking-tight whitespace-nowrap flex-shrink-0">
                            Get My Instant Quote
                            <span class="w-7 h-7 bg-[#f6e304] rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 text-[#081d3a]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 17L17 7M17 7H7M17 7v10"/>
                                </svg>
                            </span>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
