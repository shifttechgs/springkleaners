<section class="bg-[#0d1b33] section-py" id="why-us">

    <style>
        @media (min-width: 1024px) {
            .sk-bento {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                grid-template-rows: repeat(6, minmax(105px, auto));
                gap: 12px;
            }
            .sk-b1 { grid-column: 1; grid-row: 1 / 3; }
            .sk-b2 { grid-column: 1; grid-row: 3 / 5; }
            .sk-b3 { grid-column: 1; grid-row: 5 / 7; }
            .sk-b4 { grid-column: 2; grid-row: 1 / 4; }
            .sk-b5 { grid-column: 2; grid-row: 4 / 7; }
            .sk-b6 { grid-column: 3; grid-row: 1 / 3; }
            .sk-b7 { grid-column: 3; grid-row: 3 / 5; }
            .sk-b8 { grid-column: 3; grid-row: 5 / 7; }
        }
        .sk-stat {
            font-size: clamp(48px, 5.5vw, 72px);
            font-weight: 900;
            line-height: 1;
            letter-spacing: -0.04em;
        }
    </style>

    <div class="section-wrap">

        <div class="text-center mb-14 max-w-2xl mx-auto wow fadeInUp" data-wow-duration="0.7s">
            <div class="w-8 h-[2px] bg-[#f6e304] mb-3 mx-auto"></div>
            <p class="text-white/70 text-[11px] font-semibold uppercase tracking-[0.18em] mb-4">Why It Works</p>
            <h2 class="text-5xl lg:text-[62px] font-extrabold text-white leading-[1.1] tracking-tight mb-5">
                Our clients stay<br>for a reason.
            </h2>
            <p class="text-white/50 text-[15px] leading-relaxed">
                A clean space isn't the end — it's the beginning of something better.
            </p>
        </div>

        <div class="sk-bento flex flex-col gap-3 wow fadeIn" data-wow-duration="0.9s" data-wow-delay="0.2s">

            {{-- B1: Gold — Loved by Clients --}}
            <div class="sk-b1 bg-[#f6e304] rounded-2xl p-6 flex flex-col justify-between" style="min-height:190px">
                <span class="text-[#081d3a] text-[11px] font-bold uppercase tracking-widest">Loved By Clients</span>
                <div class="flex items-end gap-4 mt-3">
                    <span class="sk-stat text-[#081d3a]">90%</span>
                    <span class="text-[#081d3a]/65 text-[13px] leading-snug pb-1" style="max-width:130px">of our bookings are from repeat clients.</span>
                </div>
            </div>

            {{-- B2: Image --}}
            <div class="sk-b2 rounded-2xl overflow-hidden bg-[#0a1628]" style="min-height:210px">
                <img src="/images/works/1.png" alt="SpringKleaners professional at work" class="w-full h-full object-cover" style="min-height:210px;display:block">
            </div>

            {{-- B3: White — Experience --}}
            <div class="sk-b3 bg-white rounded-2xl p-6 flex flex-col" style="min-height:190px">
                <div class="flex-1">
                    <span class="sk-stat text-[#081d3a]">3+</span>
                    <p class="text-[#081d3a] text-[11px] font-bold uppercase tracking-widest mt-2 mb-4">Years Of Experience</p>
                    <hr class="border-[#081d3a]/10">
                </div>
                <p class="text-[#647082] text-[13px] leading-relaxed mt-4">We don't just clean, we obsess over detail.</p>
            </div>

            {{-- B4: Cream — Cleans Completed (3 rows tall) --}}
            <div class="sk-b4 bg-[#f0efe9] rounded-2xl p-7 flex flex-col justify-between" style="min-height:300px">
                <svg viewBox="0 0 64 54" class="w-14 h-12 flex-shrink-0" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g transform="translate(6,11)"><line x1="0" y1="-5" x2="0" y2="5" stroke="#081d3a" stroke-width="1.3" stroke-linecap="round"/><line x1="-5" y1="0" x2="5" y2="0" stroke="#081d3a" stroke-width="1.3" stroke-linecap="round"/><line x1="-3.5" y1="-3.5" x2="3.5" y2="3.5" stroke="#081d3a" stroke-width="0.7" stroke-linecap="round" opacity="0.45"/><line x1="3.5" y1="-3.5" x2="-3.5" y2="3.5" stroke="#081d3a" stroke-width="0.7" stroke-linecap="round" opacity="0.45"/></g>
                    <g transform="translate(54,9)"><line x1="0" y1="-4" x2="0" y2="4" stroke="#081d3a" stroke-width="1" stroke-linecap="round"/><line x1="-4" y1="0" x2="4" y2="0" stroke="#081d3a" stroke-width="1" stroke-linecap="round"/></g>
                    <g transform="translate(59,40)"><line x1="0" y1="-3" x2="0" y2="3" stroke="#081d3a" stroke-width="0.8" stroke-linecap="round" opacity="0.5"/><line x1="-3" y1="0" x2="3" y2="0" stroke="#081d3a" stroke-width="0.8" stroke-linecap="round" opacity="0.5"/></g>
                    <circle cx="32" cy="32" r="16" stroke="#081d3a" stroke-width="1.5"/>
                    <line x1="32" y1="32" x2="32" y2="21" stroke="#081d3a" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="32" y1="32" x2="40" y2="36" stroke="#081d3a" stroke-width="1.5" stroke-linecap="round"/>
                    <circle cx="32" cy="32" r="1.8" fill="#081d3a"/>
                </svg>
                <div>
                    <span class="sk-stat text-[#081d3a]">500+</span>
                    <p class="text-[#081d3a] text-[11px] font-bold uppercase tracking-widest mt-2 mb-4">Cleans Completed</p>
                    <hr class="border-[#081d3a]/10">
                    <p class="text-[#647082] text-[13px] leading-relaxed mt-4">Hundreds of homes and businesses across Cape Town's Northern Suburbs trust us with their clean.</p>
                </div>
            </div>

            {{-- B5: Gold — Trained Experts (3 rows tall) --}}
            <div class="sk-b5 bg-[#f6e304] rounded-2xl p-7 flex flex-col justify-between" style="min-height:210px">
                <svg viewBox="0 0 64 50" class="w-14 h-11 flex-shrink-0" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g transform="translate(4,11)"><line x1="0" y1="-5" x2="0" y2="5" stroke="#081d3a" stroke-width="1.3" stroke-linecap="round"/><line x1="-5" y1="0" x2="5" y2="0" stroke="#081d3a" stroke-width="1.3" stroke-linecap="round"/><line x1="-3.5" y1="-3.5" x2="3.5" y2="3.5" stroke="#081d3a" stroke-width="0.7" stroke-linecap="round" opacity="0.45"/><line x1="3.5" y1="-3.5" x2="-3.5" y2="3.5" stroke="#081d3a" stroke-width="0.7" stroke-linecap="round" opacity="0.45"/></g>
                    <g transform="translate(32,6)"><line x1="0" y1="-4" x2="0" y2="4" stroke="#081d3a" stroke-width="1" stroke-linecap="round"/><line x1="-4" y1="0" x2="4" y2="0" stroke="#081d3a" stroke-width="1" stroke-linecap="round"/></g>
                    <g transform="translate(58,12)"><line x1="0" y1="-3" x2="0" y2="3" stroke="#081d3a" stroke-width="0.9" stroke-linecap="round" opacity="0.6"/><line x1="-3" y1="0" x2="3" y2="0" stroke="#081d3a" stroke-width="0.9" stroke-linecap="round" opacity="0.6"/></g>
                    <circle cx="14" cy="27" r="6" stroke="#081d3a" stroke-width="1.4"/>
                    <path d="M3 46 Q3 37 14 37 Q25 37 25 46" stroke="#081d3a" stroke-width="1.4" stroke-linecap="round" fill="none"/>
                    <circle cx="32" cy="25" r="7" stroke="#081d3a" stroke-width="1.4"/>
                    <path d="M19 46 Q19 35 32 35 Q45 35 45 46" stroke="#081d3a" stroke-width="1.4" stroke-linecap="round" fill="none"/>
                    <circle cx="50" cy="27" r="6" stroke="#081d3a" stroke-width="1.4"/>
                    <path d="M39 46 Q39 37 50 37 Q61 37 61 46" stroke="#081d3a" stroke-width="1.4" stroke-linecap="round" fill="none"/>
                </svg>
                <div>
                    <p class="text-[#081d3a] font-black tracking-tight leading-none" style="font-size:28px">TRAINED</p>
                    <p class="text-[#081d3a] text-[11px] font-bold uppercase tracking-widest mt-1 mb-4">Experts Only</p>
                    <hr class="border-[#081d3a]/20">
                    <p class="text-[#081d3a]/65 text-[13px] leading-relaxed mt-4">No temps. No shortcuts. Just pros trained to impress.</p>
                </div>
            </div>

            {{-- B6: Gold — Client Satisfaction --}}
            <div class="sk-b6 bg-[#f6e304] rounded-2xl p-6 flex flex-col justify-between" style="min-height:190px">
                <span class="text-[#081d3a] text-[11px] font-bold uppercase tracking-widest">Client Satisfaction</span>
                <div class="flex items-end gap-4 mt-3">
                    <span class="sk-stat text-[#081d3a]">98%</span>
                    <span class="text-[#081d3a]/65 text-[13px] leading-snug pb-1" style="max-width:120px">of clients rate their clean 5 stars.</span>
                </div>
            </div>

            {{-- B7: White + image — Free Inspection --}}
            <div class="sk-b7 bg-white rounded-2xl p-5 flex flex-col" style="min-height:210px">
                <p class="text-[#081d3a] font-bold text-[15px] tracking-tight">Free Inspection</p>
                <p class="text-[#647082] text-[13px] mt-1 mb-3 leading-snug">We visit before we quote — no commitment, no surprises.</p>
                <div class="flex-1 rounded-xl overflow-hidden bg-[#e5e5e0]" style="min-height:110px">
                    <img src="/images/works/3.png" alt="Free property inspection" class="w-full h-full object-cover" style="display:block">
                </div>
            </div>

            {{-- B8: Gold — Fast Response --}}
            <div class="sk-b8 bg-[#f6e304] rounded-2xl p-6 flex flex-col justify-between" style="min-height:190px">
                <span class="text-[#081d3a] text-[11px] font-bold uppercase tracking-widest">Fast Response</span>
                <div>
                    <span class="text-[#081d3a] font-black leading-none tracking-tight" style="font-size:clamp(36px,4.5vw,54px)">INSTANT</span>
                    <hr class="border-[#081d3a]/20 my-3">
                    <p class="text-[#081d3a]/65 text-[13px] leading-relaxed">Get your instant price estimate the moment you submit.</p>
                </div>
            </div>

        </div>

        {{-- Bottom CTA --}}
        <div class="text-center mt-16 wow fadeInUp" data-wow-duration="0.7s" data-wow-delay="0.3s">
            <p class="text-white/40 text-[11px] font-bold uppercase tracking-widest mb-6">
                Ready to get your space spotlessly clean?
            </p>
            <a href="{{ route('booking.show') }}"
               class="inline-flex items-center gap-3 bg-[#f6e304] text-[#081d3a] font-bold px-8 py-4 rounded-full hover:bg-yellow-300 active:scale-95 transition-all shadow-lg text-[15px] tracking-tight">
                Get My Instant Quote
                <span class="w-7 h-7 bg-[#081d3a] rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-3.5 h-3.5 text-[#f6e304]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 17L17 7M17 7H7M17 7v10"/>
                    </svg>
                </span>
            </a>
        </div>

    </div>
</section>
