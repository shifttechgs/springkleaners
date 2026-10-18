@extends('layouts.app')
@section('title', 'Areas We Serve | Cape Town Cleaning Services by Suburb | SpringKleaners')
@section('description', 'SpringKleaners provides deep cleaning, end-of-tenancy, post-construction and specialist cleaning services across 19 Cape Town suburbs — Northern Suburbs, Blaauwberg and the Atlantic Seaboard. Find your area and book online.')
@section('content')
    @php
        $allAreas = $clusters->collapse();

        $itemListJsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'name' => 'Cape Town areas served by SpringKleaners',
            'itemListElement' => $allAreas->keys()->values()->map(fn ($slug, $i) => [
                '@type' => 'ListItem',
                'position' => $i + 1,
                'name' => $allAreas[$slug]['name'],
                'url' => route('areas.show', $slug),
            ])->all(),
        ];

        $breadcrumbJsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Areas We Serve', 'item' => url()->current()],
            ],
        ];

        $areaFaqs = config('faq.areas.faqs', []);

        $faqJsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => collect($areaFaqs)->map(fn ($faq) => [
                '@type' => 'Question',
                'name' => $faq['q'],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq['a']],
            ])->all(),
        ];
    @endphp
    <script type="application/ld+json">{!! json_encode($itemListJsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    <script type="application/ld+json">{!! json_encode($breadcrumbJsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @if (count($areaFaqs))
    <script type="application/ld+json">{!! json_encode($faqJsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endif

    @include('components.navbar')

    {{-- Hero --}}
    <section class="bg-[#081d3a] pt-36 pb-16 lg:pt-44 lg:pb-20 relative overflow-hidden">
        <div class="absolute inset-0 hero-pattern"></div>
        <div class="section-wrap relative z-10 max-w-3xl">
            <div class="flex items-center gap-2 text-white/60 text-[12px] font-medium mb-6">
                <a href="/" class="hover:text-[#f6e304] transition-colors">Home</a>
                <span>/</span>
                <span class="text-white/60">Areas We Serve</span>
            </div>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-[1.08] tracking-tight mb-6 wow fadeInUp" data-wow-duration="0.7s">
                Cleaning services across {{ $totalAreas }} Cape Town suburbs.
            </h1>
            <p class="text-white/60 text-[16px] leading-relaxed mb-8 wow fadeInUp" data-wow-duration="0.7s" data-wow-delay="0.1s">
                SpringKleaners provides deep cleaning, end-of-tenancy, post-construction, office &amp; commercial and specialist cleaning services (carpet, upholstery, window and more) across {{ $totalAreas }} suburbs spanning the Northern Suburbs, Blaauwberg and the Atlantic Seaboard — every service priced upfront, booked online, and confirmed with a free inspection.
            </p>
            <div class="flex flex-wrap gap-4 wow fadeInUp" data-wow-duration="0.7s" data-wow-delay="0.2s">
                <x-arrow-button href="{{ route('booking.show') }}" class="flex-shrink-0">Get My Instant Quote</x-arrow-button>
                <x-arrow-button variant="outline" href="https://wa.me/27815274711" target="_blank" rel="noopener noreferrer" class="flex-shrink-0">WhatsApp Us</x-arrow-button>
            </div>
            <x-trust-badges class="mt-6 wow fadeInUp" data-wow-duration="0.7s" data-wow-delay="0.25s" />
        </div>
    </section>

    {{-- Area clusters --}}
    <section class="bg-white section-py">
        <div class="section-wrap">
            @foreach ($clusters as $clusterName => $clusterAreas)
            @if ($clusterAreas->isNotEmpty())
            <div class="mb-14 last:mb-0 wow fadeInUp" data-wow-duration="0.7s">
                <div class="flex items-center gap-3 mb-7">
                    <div class="w-8 h-[2px] bg-[#f6e304]"></div>
                    <h2 class="text-[#081d3a] text-[13px] font-bold uppercase tracking-[0.14em]">{{ $clusterName }}</h2>
                    <span class="text-[#adb5c4] text-[12px] font-medium">{{ $clusterAreas->count() }} {{ Str::plural('area', $clusterAreas->count()) }}</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach ($clusterAreas as $slug => $area)
                    <a href="{{ route('areas.show', $slug) }}" class="group block bg-[#f8f9fc] border border-black/5 rounded-2xl p-6 hover:border-[#f6e304] hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
                        <h3 class="text-[#081d3a] font-extrabold text-[17px] tracking-tight mb-2 group-hover:text-[#0d2a4a]">{{ $area['name'] }}</h3>
                        <p class="text-[#5b6779] text-[13px] leading-relaxed line-clamp-2 mb-4">{{ $area['property_note'] }}</p>
                        <span class="inline-flex items-center gap-1.5 text-[#081d3a] font-bold text-[12.5px]">
                            View {{ $area['name'] }} cleaning
                            <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </span>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
            @endforeach
        </div>
    </section>

    {{-- Services cross-link --}}
    <section class="bg-[#f8f9fc] section-py">
        <div class="section-wrap">
            <div class="text-center max-w-2xl mx-auto mb-12 wow fadeInUp" data-wow-duration="0.7s">
                <div class="w-8 h-[2px] bg-[#f6e304] mb-3 mx-auto"></div>
                <span class="text-[#081d3a] text-[11px] font-semibold uppercase tracking-[0.18em]">Every Area, Every Service</span>
                <h2 class="text-3xl lg:text-4xl font-extrabold text-[#081d3a] tracking-tight leading-tight mt-3">
                    Every service, in every area above.
                </h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 wow fadeInUp" data-wow-duration="0.7s" data-wow-delay="0.1s">
                @foreach ($services as $slug => $service)
                <a href="{{ route('services.show', $slug) }}" class="flex items-center justify-between gap-3 bg-white border border-black/5 rounded-xl px-5 py-4 hover:border-[#f6e304] hover:shadow-md transition-all duration-200 group">
                    <span class="text-[#081d3a] font-semibold text-[14px] tracking-tight">{{ $service['name'] }}</span>
                    <svg class="w-4 h-4 text-[#081d3a]/30 group-hover:text-[#081d3a] group-hover:translate-x-1 transition-all flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                @endforeach
            </div>
        </div>
    </section>

    @include('components.testimonials')

    {{-- FAQ --}}
    @if (count($areaFaqs))
    <section class="bg-white section-py">
        <div class="section-wrap">
            <div class="text-center max-w-2xl mx-auto mb-12 wow fadeInUp" data-wow-duration="0.7s">
                <div class="w-8 h-[2px] bg-[#f6e304] mb-3 mx-auto"></div>
                <span class="text-[#081d3a] text-[11px] font-semibold uppercase tracking-[0.18em]">FAQ</span>
                <h2 class="text-3xl lg:text-4xl font-extrabold text-[#081d3a] tracking-tight leading-tight mt-3">
                    Areas we serve — common questions.
                </h2>
            </div>
            <div class="max-w-2xl mx-auto space-y-5 wow fadeInUp" data-wow-duration="0.7s" data-wow-delay="0.1s">
                @foreach ($areaFaqs as $faq)
                <div>
                    <h3 class="text-[#081d3a] font-bold text-[15px] tracking-tight mb-1.5">{{ $faq['q'] }}</h3>
                    <p class="text-[#5b6779] text-[14px] leading-relaxed">{{ $faq['a'] }}</p>
                </div>
                @endforeach
            </div>
            <div class="max-w-2xl mx-auto mt-8 text-center wow fadeInUp" data-wow-duration="0.7s" data-wow-delay="0.15s">
                <a href="{{ route('faq') }}#areas" class="text-[#081d3a] font-semibold text-[13px] hover:text-[#a9791f] transition-colors">
                    See more questions in our full FAQ →
                </a>
            </div>
        </div>
    </section>
    @endif

    @include('components.final-cta')
    @include('components.footer')
@endsection
