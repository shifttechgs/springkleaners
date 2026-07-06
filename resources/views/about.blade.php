@extends('layouts.app')
@section('title', 'About Us | SpringKleaners — Cape Town Northern Suburbs')
@section('description', 'SpringKleaners is a registered, fully insured cleaning company serving Cape Town\'s Northern Suburbs. Meet the team behind 500+ completed cleans and a 4.9-star rating.')
@section('content')

    @php
        $breadcrumbJsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'About', 'item' => url()->current()],
            ],
        ];
    @endphp
    <script type="application/ld+json">{!! json_encode($breadcrumbJsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>

    @include('components.navbar')

    {{-- Hero --}}
    <section class="bg-[#081d3a] pt-36 pb-16 lg:pt-44 lg:pb-20 relative overflow-hidden">
        <div class="absolute inset-0 hero-pattern"></div>
        <div class="section-wrap relative z-10 max-w-3xl">
            <div class="flex items-center gap-2 text-white/40 text-[12px] font-medium mb-6">
                <a href="/" class="hover:text-[#f6e304] transition-colors">Home</a>
                <span>/</span>
                <span class="text-white/60">About</span>
            </div>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-[1.08] tracking-tight mb-6 wow fadeInUp" data-wow-duration="0.7s">
                A registered, insured cleaning team — not a side hustle.
            </h1>
            <p class="text-white/60 text-[16px] leading-relaxed mb-8 wow fadeInUp" data-wow-duration="0.7s" data-wow-delay="0.1s">
                SpringKleaners is a registered South African company (reg. {{ \App\Support\Company::regNo() }}) operating across Cape Town's Northern Suburbs. We built this business around one idea: quote honestly, show up on time, and clean like it's our own home.
            </p>
            <div class="flex flex-wrap gap-4 wow fadeInUp" data-wow-duration="0.7s" data-wow-delay="0.2s">
                <a href="{{ route('booking.show') }}" class="btn-gold">
                    Get My Free Estimate
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7v10"/></svg>
                </a>
                <a href="https://wa.me/27815274711" target="_blank" rel="noopener noreferrer" class="btn-outline">
                    WhatsApp Us
                </a>
            </div>
        </div>
    </section>

    {{-- Stats --}}
    <section class="bg-white section-py !py-16">
        <div class="section-wrap">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 wow fadeInUp" data-wow-duration="0.7s">
                <div class="text-center lg:text-left">
                    <p class="text-[#081d3a] font-extrabold tracking-tight" style="font-size:clamp(36px,4vw,52px)">500+</p>
                    <p class="text-[#647082] text-[13px] mt-1">Cleans completed</p>
                </div>
                <div class="text-center lg:text-left">
                    <p class="text-[#081d3a] font-extrabold tracking-tight" style="font-size:clamp(36px,4vw,52px)">3+</p>
                    <p class="text-[#647082] text-[13px] mt-1">Years in operation</p>
                </div>
                <div class="text-center lg:text-left">
                    <p class="text-[#081d3a] font-extrabold tracking-tight" style="font-size:clamp(36px,4vw,52px)">98%</p>
                    <p class="text-[#647082] text-[13px] mt-1">Rate their clean 5 stars</p>
                </div>
                <div class="text-center lg:text-left">
                    <p class="text-[#081d3a] font-extrabold tracking-tight" style="font-size:clamp(36px,4vw,52px)">4.9</p>
                    <p class="text-[#647082] text-[13px] mt-1">Average Google rating</p>
                </div>
            </div>
        </div>
    </section>

    {{-- How we work --}}
    <section class="bg-[#f7f7f5] section-py">
        <div class="section-wrap">
            <div class="text-center max-w-2xl mx-auto mb-14 wow fadeInUp" data-wow-duration="0.7s">
                <div class="w-8 h-[2px] bg-[#f6e304] mb-3 mx-auto"></div>
                <span class="text-[#081d3a] text-[11px] font-semibold uppercase tracking-[0.18em]">How We Work</span>
                <h2 class="text-3xl lg:text-4xl font-extrabold text-[#081d3a] tracking-tight leading-tight mt-3">
                    No fixed prices. No surprise call-outs. No shortcuts.
                </h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 wow fadeInUp" data-wow-duration="0.7s" data-wow-delay="0.1s">
                <div class="bg-white rounded-2xl p-6 border border-gray-100">
                    <p class="text-[#081d3a] font-bold text-[15px] mb-2">We quote after we see it</p>
                    <p class="text-[#647082] text-[13px] leading-relaxed">Every price on this site is an estimate. We confirm a final, itemised quote after a free inspection — you approve it before we start, never after.</p>
                </div>
                <div class="bg-white rounded-2xl p-6 border border-gray-100">
                    <p class="text-[#081d3a] font-bold text-[15px] mb-2">Our teams are vetted, not temp</p>
                    <p class="text-[#647082] text-[13px] leading-relaxed">Every cleaner is background-checked before joining us, and SpringKleaners carries public liability insurance on every job.</p>
                </div>
                <div class="bg-white rounded-2xl p-6 border border-gray-100">
                    <p class="text-[#081d3a] font-bold text-[15px] mb-2">If it's not right, we come back</p>
                    <p class="text-[#647082] text-[13px] leading-relaxed">Let us know within 24 hours of a clean and we'll return to fix it — no additional charge, no argument.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Trust / credentials --}}
    <section class="bg-white section-py">
        <div class="section-wrap">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-14 items-center">
                <div class="wow fadeInUp" data-wow-duration="0.7s">
                    <div class="w-8 h-[2px] bg-[#f6e304] mb-3"></div>
                    <span class="text-[#081d3a] text-[11px] font-semibold uppercase tracking-[0.18em]">Verify Us</span>
                    <h2 class="text-3xl lg:text-4xl font-extrabold text-[#081d3a] tracking-tight leading-tight mt-3 mb-6">
                        Don't just take our word for it.
                    </h2>
                    <ul class="space-y-3 mb-6">
                        <li class="flex items-start gap-2.5 text-[#081d3a]/80 text-[14px] leading-relaxed">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#f6e304] flex-shrink-0 mt-2"></span>
                            Registered South African company — reg. {{ \App\Support\Company::regNo() }}
                        </li>
                        <li class="flex items-start gap-2.5 text-[#081d3a]/80 text-[14px] leading-relaxed">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#f6e304] flex-shrink-0 mt-2"></span>
                            Based in Parklands, servicing Cape Town's Northern Suburbs
                        </li>
                        <li class="flex items-start gap-2.5 text-[#081d3a]/80 text-[14px] leading-relaxed">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#f6e304] flex-shrink-0 mt-2"></span>
                            Fully insured with background-checked staff
                        </li>
                        <li class="flex items-start gap-2.5 text-[#081d3a]/80 text-[14px] leading-relaxed">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#f6e304] flex-shrink-0 mt-2"></span>
                            4.9-star average across real Google reviews
                        </li>
                    </ul>
                    <a href="https://www.google.com/maps/search/?api=1&query=Spring+Kleaners+1+Stepney+Rd+Parklands+Cape+Town"
                       target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 bg-[#f8f9fc] border border-gray-200 rounded-2xl px-5 py-3.5 text-[#081d3a] font-semibold text-[14px] hover:border-[#f6e304] transition-colors">
                        <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24"><path fill="#4285F4" d="M23.52 12.27c0-.85-.08-1.67-.22-2.45H12v4.64h6.47c-.28 1.5-1.13 2.78-2.4 3.63v3h3.89c2.27-2.09 3.58-5.17 3.58-8.82z"/><path fill="#34A853" d="M12 24c3.24 0 5.96-1.07 7.95-2.9l-3.89-3.02c-1.08.72-2.45 1.15-4.06 1.15-3.13 0-5.78-2.11-6.73-4.96H1.26v3.11C3.24 21.3 7.29 24 12 24z"/><path fill="#FBBC05" d="M5.27 14.27a7.2 7.2 0 010-4.54V6.62H1.26a11.98 11.98 0 000 10.76l4.01-3.11z"/><path fill="#EA4335" d="M12 4.77c1.76 0 3.34.61 4.58 1.8l3.44-3.44C17.95 1.19 15.24 0 12 0 7.29 0 3.24 2.7 1.26 6.62l4.01 3.11C6.22 6.88 8.87 4.77 12 4.77z"/></svg>
                        Read our Google reviews
                    </a>
                </div>
                <div class="rounded-2xl overflow-hidden aspect-[4/3] wow fadeIn" data-wow-duration="0.9s" data-wow-delay="0.15s">
                    <img src="/images/works/1.png" alt="SpringKleaners team at work" class="w-full h-full object-cover">
                </div>
            </div>
        </div>
    </section>

    {{-- Services --}}
    <section class="bg-[#f8f9fc] section-py !py-16">
        <div class="section-wrap">
            <h2 class="text-2xl lg:text-3xl font-extrabold text-navy tracking-tight mb-10">What we do</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <a href="{{ route('services.show', 'deep-cleaning') }}" class="group flex items-center justify-between gap-4 bg-white border border-gray-100 rounded-2xl p-6 hover:border-[#f6e304] hover:shadow-lg transition-all">
                    <div>
                        <p class="text-[#081d3a] font-bold text-[16px] tracking-tight">Deep Cleaning</p>
                        <p class="text-[#647082] text-[13px] mt-1">A thorough, top-to-bottom reset.</p>
                    </div>
                    <span class="w-9 h-9 rounded-full border border-[#081d3a]/20 flex items-center justify-center flex-shrink-0 group-hover:bg-[#081d3a] group-hover:border-[#081d3a] transition-all">
                        <svg class="w-3.5 h-3.5 text-[#081d3a] group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17L17 7M17 7H7M17 7v10"/></svg>
                    </span>
                </a>
                <a href="{{ route('services.show', 'end-of-tenancy') }}" class="group flex items-center justify-between gap-4 bg-white border border-gray-100 rounded-2xl p-6 hover:border-[#f6e304] hover:shadow-lg transition-all">
                    <div>
                        <p class="text-[#081d3a] font-bold text-[16px] tracking-tight">End-of-Tenancy Cleaning</p>
                        <p class="text-[#647082] text-[13px] mt-1">Move-in and move-out, deposit-ready.</p>
                    </div>
                    <span class="w-9 h-9 rounded-full border border-[#081d3a]/20 flex items-center justify-center flex-shrink-0 group-hover:bg-[#081d3a] group-hover:border-[#081d3a] transition-all">
                        <svg class="w-3.5 h-3.5 text-[#081d3a] group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17L17 7M17 7H7M17 7v10"/></svg>
                    </span>
                </a>
                <a href="{{ route('services.show', 'post-construction') }}" class="group flex items-center justify-between gap-4 bg-white border border-gray-100 rounded-2xl p-6 hover:border-[#f6e304] hover:shadow-lg transition-all">
                    <div>
                        <p class="text-[#081d3a] font-bold text-[16px] tracking-tight">Post-Construction Cleaning</p>
                        <p class="text-[#647082] text-[13px] mt-1">Builders' dust and debris, gone.</p>
                    </div>
                    <span class="w-9 h-9 rounded-full border border-[#081d3a]/20 flex items-center justify-center flex-shrink-0 group-hover:bg-[#081d3a] group-hover:border-[#081d3a] transition-all">
                        <svg class="w-3.5 h-3.5 text-[#081d3a] group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17L17 7M17 7H7M17 7v10"/></svg>
                    </span>
                </a>
            </div>
        </div>
    </section>

    @include('components.areas-we-serve')
    @include('components.final-cta')
    @include('components.footer')
@endsection
