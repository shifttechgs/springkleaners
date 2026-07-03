@extends('layouts.app')
@section('title', $content['meta_title'])
@section('description', $content['meta_description'])
@section('content')
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Service",
        "serviceType": {!! json_encode($content['label']) !!},
        "name": {!! json_encode($content['label'].' | SpringKleaners') !!},
        "description": {!! json_encode($content['meta_description']) !!},
        "provider": {
            "@@type": "LocalBusiness",
            "name": "SpringKleaners",
            "telephone": "+27815274711",
            "email": "bookings@springkleaners.co.za"
        },
        "areaServed": {
            "@@type": "City",
            "name": "Cape Town, Northern Suburbs"
        },
        "offers": {
            "@@type": "Offer",
            "priceCurrency": "ZAR",
            "price": {!! json_encode($service['base_price']) !!}
        }
    }
    </script>

    @include('components.navbar')

    {{-- Hero --}}
    <section class="bg-[#081d3a] pt-36 pb-16 lg:pt-44 lg:pb-20 relative overflow-hidden">
        <div class="absolute inset-0 hero-pattern"></div>
        <div class="section-wrap relative z-10">
            <div class="flex items-center gap-2 text-white/40 text-[12px] font-medium mb-6">
                <a href="/" class="hover:text-[#f6e304] transition-colors">Home</a>
                <span>/</span>
                <a href="/#services" class="hover:text-[#f6e304] transition-colors">Services</a>
                <span>/</span>
                <span class="text-white/60">{{ $content['label'] }}</span>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                <div class="lg:col-span-8">
                    <h1 class="font-extrabold text-white leading-[1.1] tracking-tight mb-6 whitespace-normal lg:whitespace-nowrap wow fadeInUp" style="font-size: clamp(28px, 3.4vw, 46px);" data-wow-duration="0.7s">
                        {{ $content['h1_line1'] }}<br>{{ $content['h1_line2'] }}
                    </h1>
                    <p class="text-white/60 text-[16px] leading-relaxed max-w-xl mb-8 wow fadeInUp" data-wow-duration="0.7s" data-wow-delay="0.1s">
                        {{ $content['intro'] }}
                    </p>
                    <div class="flex flex-wrap gap-4 wow fadeInUp" data-wow-duration="0.7s" data-wow-delay="0.2s">
                        <a href="{{ route('booking.show', ['service' => $slug]) }}" class="btn-gold">
                            Book This Service
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7v10"/></svg>
                        </a>
                        <a href="https://wa.me/27815274711" target="_blank" rel="noopener noreferrer" class="btn-outline">
                            WhatsApp Us
                        </a>
                    </div>
                </div>
                <div class="lg:col-span-4 wow fadeIn" data-wow-duration="0.9s" data-wow-delay="0.15s">
                    <div class="rounded-2xl overflow-hidden shadow-2xl aspect-[4/3]">
                        <img src="{{ $content['hero_image'] }}" alt="{{ $content['label'] }} — SpringKleaners" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- What's included + pricing card --}}
    <section class="bg-white section-py">
        <div class="section-wrap">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14">
                <div class="lg:col-span-7 wow fadeInUp" data-wow-duration="0.7s">
                    <div class="w-8 h-[2px] bg-[#f6e304] mb-3"></div>
                    <span class="text-[#081d3a] text-[11px] font-semibold uppercase tracking-[0.18em]">What's Included</span>
                    <h2 class="text-3xl lg:text-4xl font-extrabold text-[#081d3a] tracking-tight leading-tight mt-3 mb-6">
                        Every {{ strtolower($content['label']) }} covers this as standard.
                    </h2>
                    <ul class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 mb-8">
                        @foreach ($content['included'] as $item)
                        <li class="flex items-start gap-2.5 text-[#081d3a]/75 text-[14px] leading-relaxed">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#f6e304] flex-shrink-0 mt-2"></span>
                            {{ $item }}
                        </li>
                        @endforeach
                    </ul>
                    <p class="text-[#647082] text-[14px] leading-relaxed max-w-lg">
                        <span class="font-semibold text-[#081d3a]">Ideal for:</span> {{ $content['ideal_for'] }}
                    </p>
                </div>

                <div class="lg:col-span-5 wow fadeInUp" data-wow-duration="0.7s" data-wow-delay="0.15s">
                    <div class="bg-[#0d1b33] rounded-2xl p-7 lg:p-8 sticky top-28">
                        <span class="text-white/50 text-[12px] font-medium">From</span>
                        <div class="flex items-baseline gap-2 mt-1 mb-5">
                            <span class="text-white text-[42px] font-extrabold tracking-tighter leading-none">R{{ number_format($service['base_price']) }}</span>
                            <span class="text-white/50 text-[13px]">/ {{ $service['unit_label'] }}</span>
                        </div>
                        <ul class="space-y-2.5 mb-6 pb-6 border-b border-white/10">
                            <li class="flex justify-between text-[13px] text-white/70">
                                <span>Includes</span>
                                <span class="text-white font-semibold">{{ $service['included_bedrooms'] }} bed, {{ $service['included_bathrooms'] }} bath</span>
                            </li>
                            <li class="flex justify-between text-[13px] text-white/70">
                                <span>Average time</span>
                                <span class="text-white font-semibold">{{ $service['avg_hours'] }} hrs</span>
                            </li>
                            <li class="flex justify-between text-[13px] text-white/70">
                                <span>Extra bedroom</span>
                                <span class="text-white font-semibold">+R{{ $service['bedroom_price'] }}</span>
                            </li>
                            <li class="flex justify-between text-[13px] text-white/70">
                                <span>Extra bathroom</span>
                                <span class="text-white font-semibold">+R{{ $service['bathroom_price'] }}</span>
                            </li>
                        </ul>
                        <a href="{{ route('booking.show', ['service' => $slug]) }}" class="w-full flex items-center justify-center gap-2 bg-[#f6e304] text-[#081d3a] font-bold py-3.5 rounded-xl hover:bg-yellow-300 active:scale-95 transition-all text-[14px]">
                            Book This Service
                        </a>
                        <p class="text-center text-white/40 text-[11px] mt-3">Free inspection · Confirmed quote before we start</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('components.how-it-works')
    @include('components.why-us')
    @include('components.testimonials')

    {{-- Service FAQ --}}
    <section class="bg-[#f7f7f5] section-py">
        <div class="section-wrap">
            <div class="text-center max-w-2xl mx-auto mb-12 wow fadeInUp" data-wow-duration="0.7s">
                <div class="w-8 h-[2px] bg-[#f6e304] mb-3 mx-auto"></div>
                <span class="text-[#081d3a] text-[11px] font-semibold uppercase tracking-[0.18em]">FAQ</span>
                <h2 class="text-3xl lg:text-4xl font-extrabold text-[#081d3a] tracking-tight leading-tight mt-3">
                    {{ $content['label'] }} — common questions.
                </h2>
            </div>
            <div class="max-w-2xl mx-auto space-y-2.5 wow fadeInUp" data-wow-duration="0.7s" data-wow-delay="0.1s" x-data="{ active: 0 }">
                @foreach ($content['faqs'] as $i => $faq)
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

    @if ($posts->isNotEmpty())
    <section class="bg-white section-py !py-16 border-t border-black/5">
        <div class="section-wrap">
            <div class="flex items-end justify-between mb-10">
                <h2 class="text-2xl lg:text-3xl font-extrabold text-navy tracking-tight">Related reading</h2>
                <a href="{{ route('blog.index') }}" class="text-navy font-bold text-[13px] tracking-tight hidden sm:inline-flex items-center gap-2 hover:opacity-70 transition-opacity">
                    View all posts
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($posts as $post)
                <x-blog-card :post="$post" />
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Other services --}}
    <section class="bg-[#f8f9fc] section-py !py-16">
        <div class="section-wrap">
            <h2 class="text-2xl lg:text-3xl font-extrabold text-navy tracking-tight mb-10">Other services</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                @foreach ($allServices as $otherSlug => $other)
                    @continue($otherSlug === $slug)
                    <a href="{{ route('services.show', $otherSlug) }}" class="group flex items-center justify-between gap-4 bg-white border border-gray-100 rounded-2xl p-6 hover:border-[#f6e304] hover:shadow-lg transition-all">
                        <div>
                            <p class="text-[#081d3a] font-bold text-[16px] tracking-tight">{{ $other['name'] }}</p>
                            <p class="text-[#647082] text-[13px] mt-1">{{ $other['tagline'] }}</p>
                        </div>
                        <span class="w-9 h-9 rounded-full border border-[#081d3a]/20 flex items-center justify-center flex-shrink-0 group-hover:bg-[#081d3a] group-hover:border-[#081d3a] transition-all">
                            <svg class="w-3.5 h-3.5 text-[#081d3a] group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17L17 7M17 7H7M17 7v10"/></svg>
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    @include('components.final-cta')
    @include('components.footer')
@endsection
