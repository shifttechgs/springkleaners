<section class="bg-[#f8f9fc] section-py">
    <div class="section-wrap">

        {{-- Header row --}}
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8 mb-12 wow fadeInUp" data-wow-duration="0.7s">
            <div class="max-w-lg">
                <div class="w-8 h-[2px] bg-[#f6e304] mb-3"></div>
                <span class="text-[#081d3a] text-[11px] font-semibold uppercase tracking-[0.18em]">Who We Help</span>
                <h2 class="text-[38px] sm:text-[48px] lg:text-[56px] font-semibold text-[#081d3a] leading-[1.1] tracking-[-0.02em] mt-3 mb-4">
                    Spaces we<br>specialize in
                </h2>
                <p class="text-[#647082] text-[15px] leading-relaxed max-w-sm font-normal">
                    We work with busy homeowners, growing businesses, and property owners who need a space that's consistently clean and well cared for.
                </p>
            </div>
            <div class="flex-shrink-0">
                <a href="/get-my-quote"
                   class="inline-flex items-center gap-3 bg-[#f6e304] text-[#081d3a] font-bold px-6 py-3.5 rounded-full hover:bg-yellow-300 active:scale-95 transition-all text-[14px] tracking-tight">
                    Get My Instant Quote
                    <span class="w-7 h-7 bg-[#081d3a] rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-3.5 h-3.5 text-[#f6e304]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 17L17 7M17 7H7M17 7v10"/>
                        </svg>
                    </span>
                </a>
            </div>
        </div>

        {{-- Three cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 wow fadeInUp" data-wow-duration="0.7s" data-wow-delay="0.15s">

            {{-- Card 1: Residential Homes --}}
            <div class="bg-white rounded-2xl p-7 border border-gray-100 hover:shadow-sm transition-all duration-300">
                <div class="w-11 h-11 mb-5">
                    <svg viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
                        <rect width="44" height="44" rx="10" fill="#f6e304" fill-opacity="0.12"/>
                        <path d="M22 12L10 22h3v10h7v-6h4v6h7V22h3L22 12z" stroke="#081d3a" stroke-width="1.4" stroke-linejoin="round" fill="none"/>
                        <path d="M19 32v-5h6v5" stroke="#081d3a" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <p class="text-[#081d3a] font-bold uppercase tracking-wider text-[13px] mb-3">Home</p>
                <p class="text-[#647082] text-[13px] leading-relaxed">
                    Apartments, houses &amp; townhouses — busy homeowners who want their space consistently fresh and spotless.
                </p>
            </div>

            {{-- Card 2: Commercial / Office --}}
            <div class="bg-white rounded-2xl p-7 border border-gray-100 hover:shadow-sm transition-all duration-300">
                <div class="w-11 h-11 mb-5">
                    <svg viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
                        <rect width="44" height="44" rx="10" fill="#f6e304" fill-opacity="0.12"/>
                        <rect x="10" y="14" width="24" height="18" rx="1.5" stroke="#081d3a" stroke-width="1.4" fill="none"/>
                        <path d="M10 19h24" stroke="#081d3a" stroke-width="1.4"/>
                        <circle cx="16" cy="16.5" r="1" fill="#081d3a"/>
                        <circle cx="20" cy="16.5" r="1" fill="#081d3a"/>
                        <rect x="14" y="23" width="6" height="5" rx="1" stroke="#081d3a" stroke-width="1.2" fill="none"/>
                        <path d="M24 24h4M24 27h4" stroke="#081d3a" stroke-width="1.2" stroke-linecap="round"/>
                    </svg>
                </div>
                <p class="text-[#081d3a] font-bold uppercase tracking-wider text-[13px] mb-3">Workspace</p>
                <p class="text-[#647082] text-[13px] leading-relaxed">
                    Offices, clinics &amp; commercial spaces — maintain a clean, professional environment that supports your team and impresses clients.
                </p>
            </div>

            {{-- Card 3: Post-Build / Rental Turnover --}}
            <div class="bg-white rounded-2xl p-7 border border-gray-100 hover:shadow-sm transition-all duration-300">
                <div class="w-11 h-11 mb-5">
                    <svg viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
                        <rect width="44" height="44" rx="10" fill="#f6e304" fill-opacity="0.12"/>
                        <rect x="12" y="18" width="20" height="14" rx="1" stroke="#081d3a" stroke-width="1.4" fill="none"/>
                        <path d="M9 18h26" stroke="#081d3a" stroke-width="1.4" stroke-linecap="round"/>
                        <path d="M18 18V14h8v4" stroke="#081d3a" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M19 25h6M22 22v6" stroke="#081d3a" stroke-width="1.3" stroke-linecap="round"/>
                    </svg>
                </div>
                <p class="text-[#081d3a] font-bold uppercase tracking-wider text-[13px] mb-3">Property</p>
                <p class="text-[#647082] text-[13px] leading-relaxed">
                    New builds, rental turnovers &amp; post-renovation handovers — move-in ready from day one, every time.
                </p>
            </div>

        </div>

    </div>
</section>
