@extends('layouts.app')
@section('title', 'Cleaning Services in '.$location['name'].' | SpringKleaners')
@section('description', $location['meta_description'])
@section('content')
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Service",
        "serviceType": "Residential & Commercial Cleaning",
        "name": {!! json_encode('Cleaning Services in '.$location['name']) !!},
        "description": {!! json_encode($location['meta_description']) !!},
        "provider": {
            "@@type": "LocalBusiness",
            "name": "SpringKleaners",
            "telephone": "+27815274711",
            "email": "bookings@springkleaners.co.za"
        },
        "areaServed": {
            "@@type": "Place",
            "name": {!! json_encode($location['name'].', Cape Town') !!}
        }
    }
    </script>

    @php
        $breadcrumbJsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Areas We Serve', 'item' => url('/#areas')],
                ['@type' => 'ListItem', 'position' => 3, 'name' => $location['name'], 'item' => url()->current()],
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
                <a href="/#areas" class="hover:text-[#f6e304] transition-colors">Areas We Serve</a>
                <span>/</span>
                <span class="text-white/60">{{ $location['name'] }}</span>
            </div>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-[1.08] tracking-tight mb-6 wow fadeInUp" data-wow-duration="0.7s">
                Cleaning Services in {{ $location['name'] }}, Cape Town
            </h1>
            <p class="text-white/60 text-[16px] leading-relaxed mb-4 wow fadeInUp" data-wow-duration="0.7s" data-wow-delay="0.1s">
                {{ $location['blurb'] }}
            </p>
            <p class="text-white/40 text-[13px] mb-8 wow fadeInUp" data-wow-duration="0.7s" data-wow-delay="0.15s">
                {{ $location['property_note'] }}
            </p>
            <div class="flex flex-wrap gap-4 wow fadeInUp" data-wow-duration="0.7s" data-wow-delay="0.2s">
                <a href="{{ route('booking.show', ['suburb' => $location['name']]) }}" class="btn-gold">
                    Get My Free Estimate
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7v10"/></svg>
                </a>
                <a href="https://wa.me/27815274711" target="_blank" rel="noopener noreferrer" class="btn-outline">
                    WhatsApp Us
                </a>
            </div>
        </div>
    </section>

    {{-- Services in this area --}}
    <section class="bg-white section-py">
        <div class="section-wrap">
            <div class="text-center max-w-2xl mx-auto mb-12 wow fadeInUp" data-wow-duration="0.7s">
                <div class="w-8 h-[2px] bg-[#f6e304] mb-3 mx-auto"></div>
                <span class="text-[#081d3a] text-[11px] font-semibold uppercase tracking-[0.18em]">Our Services</span>
                <h2 class="text-3xl lg:text-4xl font-extrabold text-[#081d3a] tracking-tight leading-tight mt-3">
                    Available in {{ $location['name'] }}
                </h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 wow fadeInUp" data-wow-duration="0.7s" data-wow-delay="0.15s">
                @foreach ($services as $svcSlug => $svc)
                <div class="bg-[#f8f9fc] border border-gray-100 rounded-2xl p-6 flex flex-col">
                    <p class="text-[#081d3a] font-bold text-[16px] tracking-tight mb-2">{{ $svc['name'] }}</p>
                    <p class="text-[#647082] text-[13px] leading-relaxed mb-5 flex-1">{{ $svc['tagline'] }}</p>
                    <p class="text-[#647082] text-[11px] mb-4">From <span class="text-[#081d3a] font-bold">R{{ number_format($svc['base_price']) }}</span> / {{ $svc['unit_label'] }}</p>
                    <div class="flex flex-col gap-2">
                        <a href="{{ route('booking.show', ['service' => $svcSlug, 'suburb' => $location['name']]) }}" class="text-center bg-[#081d3a] text-[#f6e304] font-semibold py-2.5 rounded-full hover:bg-[#0d2a4a] transition-all text-[13px]">
                            Book in {{ $location['name'] }}
                        </a>
                        <a href="{{ route('services.show', $svcSlug) }}" class="text-center text-[#081d3a]/60 font-semibold py-1 text-[12px] hover:text-[#081d3a] transition-colors">
                            Learn more →
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    @include('components.why-us')
    @include('components.testimonials')

    {{-- Area FAQ --}}
    <section class="bg-[#f7f7f5] section-py">
        <div class="section-wrap">
            <div class="text-center max-w-2xl mx-auto mb-12 wow fadeInUp" data-wow-duration="0.7s">
                <div class="w-8 h-[2px] bg-[#f6e304] mb-3 mx-auto"></div>
                <span class="text-[#081d3a] text-[11px] font-semibold uppercase tracking-[0.18em]">FAQ</span>
                <h2 class="text-3xl lg:text-4xl font-extrabold text-[#081d3a] tracking-tight leading-tight mt-3">
                    Cleaning in {{ $location['name'] }} — common questions.
                </h2>
            </div>
            @php
                $areaFaqs = [
                    ['q' => 'Do you actually service '.$location['name'].'?', 'a' => 'Yes — '.$location['name'].' is one of our regularly serviced areas across Cape Town\'s Northern Suburbs. Enter your address when booking and we\'ll confirm scheduling.'],
                    ['q' => 'How do you calculate the final price?', 'a' => 'We don\'t charge a fixed rate because every property is different. After you contact us, we arrange a free, no-obligation site inspection, then provide a detailed, itemised quote you approve before we start.'],
                    ['q' => 'Can I get a same-week booking?', 'a' => 'In most cases, yes. Message us on WhatsApp with your address in '.$location['name'].' and we\'ll check availability immediately.'],
                    ['q' => 'Are your cleaning teams insured?', 'a' => 'Yes. SpringKleaners is fully insured, and every team member is background-checked before joining us.'],
                ];

                $faqJsonLd = [
                    '@context' => 'https://schema.org',
                    '@type' => 'FAQPage',
                    'mainEntity' => collect($areaFaqs)->map(fn ($faq) => [
                        '@type' => 'Question',
                        'name' => $faq['q'],
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => $faq['a'],
                        ],
                    ])->all(),
                ];
            @endphp
            <script type="application/ld+json">{!! json_encode($faqJsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
            <div class="max-w-2xl mx-auto space-y-2.5 wow fadeInUp" data-wow-duration="0.7s" data-wow-delay="0.1s" x-data="{ active: 0 }">
                @foreach ($areaFaqs as $i => $faq)
                <div class="rounded-xl transition-all duration-200" :class="active === {{ $i }} ? 'bg-white shadow-sm ring-1 ring-gray-200' : 'bg-white/60 hover:bg-white ring-1 ring-transparent hover:ring-gray-100'">
                    <button @click="active === {{ $i }} ? active = null : active = {{ $i }}" class="w-full flex justify-between items-center px-5 py-4 text-left gap-4">
                        <span class="text-[#081d3a] font-semibold text-[14px] tracking-tight">{{ $faq['q'] }}</span>
                        <span class="w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0 transition-all duration-200" :class="active === {{ $i }} ? 'bg-[#f6e304] text-[#081d3a] rotate-45' : 'border border-gray-300 text-gray-400'">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12M6 12h12"/></svg>
                        </span>
                    </button>
                    <div x-show="active === {{ $i }}" x-transition class="px-5 pb-4" style="display:none;">
                        <p class="text-[#647082] text-[13px] leading-relaxed">{{ $faq['a'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Other areas --}}
    <section class="bg-[#f8f9fc] section-py !py-16">
        <div class="section-wrap">
            <h2 class="text-2xl lg:text-3xl font-extrabold text-navy tracking-tight mb-10">Other areas we serve</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                @foreach ($otherLocations as $otherSlug => $other)
                <a href="{{ route('areas.show', $otherSlug) }}" class="flex items-center gap-3 bg-white border border-gray-100 rounded-2xl px-5 py-4 hover:border-[#f6e304] hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
                    <svg class="w-4 h-4 text-[#081d3a]/50 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                    <span class="text-[#081d3a] font-semibold text-[14px] tracking-tight">{{ $other['name'] }}</span>
                </a>
                @endforeach
            </div>
        </div>
    </section>

    @include('components.final-cta')
    @include('components.footer')
@endsection
