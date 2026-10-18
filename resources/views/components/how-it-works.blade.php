<section id="how-it-works" class="bg-[#f7f7f5] section-py overflow-hidden">
    <div class="section-wrap">

        {{-- Row 1: Intro + Steps 1 & 2 --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-5">

            {{-- Intro --}}
            <div class="flex flex-col justify-center wow fadeInUp" data-wow-duration="0.7s">
                <div class="w-8 h-[2px] bg-[#f6e304] mb-3"></div>
                <span class="text-[#081d3a] text-[11px] font-semibold uppercase tracking-[0.18em]">How It Works</span>
                <h2 class="text-3xl lg:text-[38px] font-extrabold text-[#081d3a] tracking-tight leading-[1.1] mt-3 mb-3">
                    Get cleaner space in four steps
                </h2>
                <p class="text-[#5b6779] text-[14px] leading-relaxed">
                    And sometimes, in as little as 24 hours.
                </p>
            </div>

            {{-- Step 1 --}}
            <div class="bg-white rounded-xl p-5 flex flex-col wow fadeInUp" data-wow-duration="0.7s" data-wow-delay="0.1s">
                <span class="step-num text-[#081d3a]/8 font-black text-[48px] leading-none tracking-tighter" data-n="01" aria-hidden="true"></span>
                <h3 class="text-[#081d3a] text-[15px] font-bold uppercase tracking-tight mt-2 mb-1.5">Share Details</h3>
                <p class="text-[#5b6779] text-[12px] leading-relaxed mb-4">Fill out a quick form with details about your space, schedule, and preferences.</p>
                {{-- A miniature of the hero quote form, filled in, so the step shows exactly what the visitor will do --}}
                <div class="mt-auto relative isolate rounded-lg overflow-hidden aspect-[4/3] flex items-center justify-center p-4" style="background: linear-gradient(135deg, #081d3a 0%, #0d2a4a 100%);" role="img" aria-label="Example of the SpringKleaners quote form filled in">
                    <div class="w-full max-w-[17rem] -rotate-2 rounded-lg bg-[#f6f8fb] p-3.5 shadow-[0_20px_40px_-12px_rgba(0,0,0,0.6)]" aria-hidden="true">
                        <p class="font-display text-[#081d3a] text-[13px] leading-none">Get your instant quote</p>
                        <div class="mt-2.5">
                            <p class="text-[#081d3a]/70 text-[8.5px] font-semibold mb-1">Your Suburb</p>
                            <div class="flex items-center justify-between rounded-md border border-[#081d3a]/15 bg-white px-2 py-1.5 text-[10px] text-[#081d3a]">
                                Milnerton
                                <svg class="h-3 w-3 text-green-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            </div>
                        </div>
                        <div class="mt-2 grid grid-cols-2 gap-2">
                            <div>
                                <p class="text-[#081d3a]/70 text-[8.5px] font-semibold mb-1">Full Name</p>
                                <div class="rounded-md border border-[#081d3a]/15 bg-white px-2 py-1.5 text-[10px] text-[#081d3a]">Jane Smith</div>
                            </div>
                            <div>
                                <p class="text-[#081d3a]/70 text-[8.5px] font-semibold mb-1">Service</p>
                                <div class="flex items-center justify-between rounded-md border border-[#f6e304] bg-white px-2 py-1.5 text-[10px] text-[#081d3a]">
                                    Deep Cleaning
                                    <svg class="h-2.5 w-2.5 text-[#081d3a]/55" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                        </div>
                        <div class="relative mt-2.5 flex items-center justify-between rounded-full bg-[#f6e304] py-1 pl-3 pr-1 text-[10px] font-semibold text-[#081d3a]">
                            Get my instant quote
                            <span class="grid h-5 w-5 place-items-center rounded-full bg-[#081d3a] text-[#f6e304]">
                                <svg class="h-2.5 w-2.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17L17 7M8 7h9v9"/></svg>
                            </span>
                            {{-- pointer, about to click --}}
                            <svg class="absolute -bottom-3 right-10 h-5 w-5 drop-shadow" viewBox="0 0 24 24"><path d="M5 3l14 7-6 2-2 6z" fill="#fff" stroke="#081d3a" stroke-width="1.5" stroke-linejoin="round"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Step 2 --}}
            <div class="bg-white rounded-xl p-5 flex flex-col wow fadeInUp" data-wow-duration="0.7s" data-wow-delay="0.2s">
                <span class="step-num text-[#081d3a]/8 font-black text-[48px] leading-none tracking-tighter" data-n="02" aria-hidden="true"></span>
                <h3 class="text-[#081d3a] text-[15px] font-bold uppercase tracking-tight mt-2 mb-1.5">Get Quote</h3>
                <p class="text-[#5b6779] text-[12px] leading-relaxed mb-4">We'll send you a personalized estimate, no hidden fees, no upselling.</p>
                <div class="mt-auto rounded-lg overflow-hidden aspect-[4/3] bg-[#e8e8e4]">
                    <img loading="lazy" decoding="async" src="/images/works/step2-get-quote.avif" alt="Woman smiling as she checks her cleaning quote on her phone" class="w-full h-full object-cover">
                </div>
            </div>

        </div>

        {{-- Row 2: Steps 3 & 4 + CTA --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-5 mt-5">

            {{-- Step 3 --}}
            <div class="bg-white rounded-xl p-5 flex flex-col wow fadeInUp" data-wow-duration="0.7s" data-wow-delay="0.3s">
                <span class="step-num text-[#081d3a]/8 font-black text-[48px] leading-none tracking-tighter" data-n="03" aria-hidden="true"></span>
                <h3 class="text-[#081d3a] text-[15px] font-bold uppercase tracking-tight mt-2 mb-1.5">We Clean</h3>
                <p class="text-[#5b6779] text-[12px] leading-relaxed mb-4">Our team arrives on time, equipped, and ready to clean thoroughly.</p>
                <div class="mt-auto rounded-lg overflow-hidden aspect-[4/3] bg-[#e8e8e4]">
                    <img loading="lazy" decoding="async" src="/images/works/real-we-clean.avif" alt="Two SpringKleaners team members cleaning a kitchen together, one wiping the tops of the tall cabinets" class="w-full h-full object-cover">
                </div>
            </div>

            {{-- Step 4 --}}
            <div class="bg-white rounded-xl p-5 flex flex-col wow fadeInUp" data-wow-duration="0.7s" data-wow-delay="0.4s">
                <span class="step-num text-[#081d3a]/8 font-black text-[48px] leading-none tracking-tighter" data-n="04" aria-hidden="true"></span>
                <h3 class="text-[#081d3a] text-[15px] font-bold uppercase tracking-tight mt-2 mb-1.5">You Relax</h3>
                <p class="text-[#5b6779] text-[12px] leading-relaxed mb-4">Enjoy a spotless home or workspace that looks, feels, and smells truly clean.</p>
                <div class="mt-auto rounded-lg overflow-hidden aspect-[4/3] bg-[#e8e8e4]">
                    <img loading="lazy" decoding="async" src="/images/works/step4-you-relax.avif" alt="The same woman relaxing on her sofa at home after the clean" class="w-full h-full object-cover">
                </div>
            </div>

            {{-- CTA --}}
            <div class="flex flex-col justify-end items-end text-right wow fadeInUp" data-wow-duration="0.7s" data-wow-delay="0.5s">
                <p class="text-[#081d3a]/40 text-[11px] font-bold uppercase tracking-widest mb-2">Ready to Get Started?</p>
                <p class="text-[#5b6779] text-[13px] leading-relaxed mb-5">Simple pricing, no hidden fees — see what works for your space.</p>
                <x-arrow-button href="/#pricing" class="flex-shrink-0">View our pricing</x-arrow-button>
            </div>

        </div>

    </div>
</section>
