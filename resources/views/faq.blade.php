@extends('layouts.app')
@section('title', 'Frequently Asked Questions | SpringKleaners')
@section('description', 'Answers on pricing, insurance, individual services, areas we serve, and how our services compare — everything you need to know before booking with SpringKleaners.')
@section('content')
    @php
        $allFaqs = collect($clusters)->flatMap(fn ($cluster) => $cluster['faqs']);

        $faqJsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $allFaqs->map(fn ($faq) => [
                '@type' => 'Question',
                'name' => $faq['q'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $faq['a'],
                ],
            ])->values()->all(),
        ];

        $breadcrumbJsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'FAQ', 'item' => url()->current()],
            ],
        ];

        $clusterIcons = [
            'pricing' => 'M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3zM7 7h.008v.008H7V7z',
            'trust' => 'M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z',
            'services' => 'M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z M18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456z',
            'areas' => 'M15 10.5a3 3 0 11-6 0 3 3 0 016 0z M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z',
            'comparison' => 'M7.5 21L3 16.5m0 0L7.5 12M3 16.5h18M16.5 3L21 7.5m0 0L16.5 12M21 7.5H3',
        ];
    @endphp
    <script type="application/ld+json">{!! json_encode($faqJsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    <script type="application/ld+json">{!! json_encode($breadcrumbJsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>

    @include('components.navbar')

    <div x-data="{ tab: ['pricing','trust','services','areas','comparison'].includes(window.location.hash.slice(1)) ? window.location.hash.slice(1) : 'pricing' }">

        {{-- Hero --}}
        <section class="bg-[#081d3a] pt-36 pb-14 lg:pt-44 lg:pb-16 relative overflow-hidden">
            <div class="absolute inset-0 hero-pattern"></div>
            <div class="section-wrap relative z-10 max-w-3xl">
                <div class="flex items-center gap-2 text-white/40 text-[12px] font-medium mb-6">
                    <a href="/" class="hover:text-[#f6e304] transition-colors">Home</a>
                    <span>/</span>
                    <span class="text-white/60">FAQ</span>
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-[1.08] tracking-tight mb-6 wow fadeInUp" data-wow-duration="0.7s">
                    Frequently Asked Questions
                </h1>
                <p class="text-white/60 text-[16px] leading-relaxed wow fadeInUp" data-wow-duration="0.7s" data-wow-delay="0.1s">
                    {{ $allFaqs->count() }} real answers, sorted so you only read what you actually need. Pick a topic below.
                </p>
            </div>
        </section>

        {{-- Tabs --}}
        <section class="bg-[#f8f9fc] border-b border-black/5 sticky top-[76px] z-30">
            <div class="section-wrap">
                <div class="flex gap-1.5 overflow-x-auto py-3 -mx-1 px-1">
                    @foreach ($clusters as $clusterKey => $cluster)
                    <button type="button"
                            @click="tab = '{{ $clusterKey }}'"
                            class="flex items-center gap-2 whitespace-nowrap px-4 py-2.5 rounded-xl text-[13px] font-semibold transition-all duration-200 flex-shrink-0"
                            :class="tab === '{{ $clusterKey }}' ? 'bg-[#081d3a] text-white shadow-md' : 'text-[#081d3a]/60 hover:bg-white hover:text-[#081d3a]'">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $clusterIcons[$clusterKey] }}"/></svg>
                        {{ $cluster['label'] }}
                        <span class="text-[10px] font-bold opacity-50">{{ count($cluster['faqs']) }}</span>
                    </button>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Panels --}}
        <section class="bg-white section-py !py-14">
            <div class="section-wrap">
                @foreach ($clusters as $clusterKey => $cluster)
                <div x-show="tab === '{{ $clusterKey }}'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="{{ $loop->first ? '' : 'display:none;' }}">
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-10 lg:gap-14 items-start">

                        {{-- Left: image + intro --}}
                        <div class="lg:col-span-2 lg:sticky lg:top-24">
                            <div class="rounded-2xl overflow-hidden bg-[#e8e8e4] mb-6 aspect-[4/3]">
                                <img src="{{ $cluster['image'] }}" alt="{{ $cluster['label'] }} — SpringKleaners" class="w-full h-full object-cover">
                            </div>
                            <div class="w-8 h-[2px] bg-[#f6e304] mb-3"></div>
                            <span class="text-[#081d3a] text-[11px] font-semibold uppercase tracking-[0.18em]">{{ $cluster['label'] }}</span>
                            <h2 class="text-2xl lg:text-3xl font-extrabold text-[#081d3a] tracking-tight leading-tight mt-3 mb-3">
                                {{ $cluster['blurb'] }}
                            </h2>
                            <a href="https://wa.me/27815274711" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 bg-[#0d1b33] text-white rounded-full px-5 py-2.5 text-[12px] font-semibold hover:bg-[#132441] transition-colors mt-2">
                                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                Ask us directly
                            </a>
                        </div>

                        {{-- Right: accordion --}}
                        <div class="lg:col-span-3 space-y-2.5" x-data="{ active: null }">
                            @php $previousGroup = null; @endphp
                            @foreach ($cluster['faqs'] as $i => $faq)
                            @if (isset($faq['group']) && $faq['group'] !== $previousGroup)
                                @php $previousGroup = $faq['group']; @endphp
                                <h3 class="text-[#081d3a]/40 text-[11px] font-bold uppercase tracking-[0.14em] {{ $loop->first ? '' : 'pt-6' }} pb-1">{{ $faq['group'] }}</h3>
                            @endif
                            <div class="rounded-xl transition-all duration-200" :class="active === {{ $i }} ? 'bg-[#f8f9fc] shadow-sm ring-1 ring-gray-200' : 'bg-white ring-1 ring-gray-100 hover:ring-gray-200'">
                                <button @click="active === {{ $i }} ? active = null : active = {{ $i }}" class="w-full flex justify-between items-center px-5 py-4 text-left gap-4">
                                    <span class="text-[#081d3a] font-semibold text-[14px] tracking-tight">{{ $faq['q'] }}</span>
                                    <span class="w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0 transition-all duration-200" :class="active === {{ $i }} ? 'bg-[#f6e304] text-[#081d3a] rotate-45' : 'border border-gray-300 text-gray-400'">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12M6 12h12"/></svg>
                                    </span>
                                </button>
                                <div x-show="active === {{ $i }}" x-transition class="px-5 pb-4" style="display:none;">
                                    <p class="text-[#647082] text-[13px] leading-relaxed">{{ $faq['a'] }}</p>
                                    @if (isset($faq['link']))
                                    <a href="{{ route($faq['link']['route'], $faq['link']['param']) }}" class="inline-flex items-center gap-1.5 text-[#081d3a] font-semibold text-[12px] mt-2.5 hover:text-[#a9791f] transition-colors">
                                        {{ $faq['link']['label'] }}
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                    </a>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>

                    </div>
                </div>
                @endforeach
            </div>
        </section>

    </div>

    {{-- Still have a question --}}
    <section class="bg-[#0d1b33] section-py !py-16">
        <div class="section-wrap text-center max-w-xl mx-auto">
            <h2 class="text-2xl lg:text-3xl font-extrabold text-white tracking-tight mb-4">Still have a question?</h2>
            <p class="text-white/50 text-[14px] mb-7">Message us on WhatsApp — we reply fast, no bots.</p>
            <a href="https://wa.me/27815274711" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 bg-[#f6e304] text-[#081d3a] font-bold px-6 py-3.5 rounded-xl hover:bg-yellow-300 active:scale-95 transition-all text-[14px]">
                WhatsApp Us
            </a>
        </div>
    </section>

    @include('components.final-cta')
    @include('components.footer')
@endsection
