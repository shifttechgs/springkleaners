@extends('layouts.app')
@section('title', 'SpringKleaners | Premium Cleaning — Cape Town Northern Suburbs')
@section('content')
    @php
        $organizationLd = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            '@id' => rtrim(config('app.url'), '/').'/#organization',
            'name' => 'SpringKleaners',
            'alternateName' => 'Spring Kleaners',
            'legalName' => 'SpringKleaners (Reg. 2021/363748/07)',
            'url' => config('app.url'),
            'logo' => url('/images/logo.webp'),
            'foundingDate' => '2021',
            'identifier' => '2021/363748/07',
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'telephone' => '+27815274711',
                'contactType' => 'customer service',
                'areaServed' => 'ZA',
                'availableLanguage' => ['en'],
            ],
        ];

        $websiteLd = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'url' => config('app.url'),
            'name' => 'SpringKleaners',
            'publisher' => ['@id' => rtrim(config('app.url'), '/').'/#business'],
        ];
    @endphp
    <script type="application/ld+json">{!! json_encode($organizationLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    <script type="application/ld+json">{!! json_encode($websiteLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>

    @include('components.navbar')
    @include('components.hero')
    @include('components.marquee')
    @include('components.who-we-help')
    @include('components.services')
    @include('components.how-it-works')
    @include('components.why-us')
    <x-before-after-gallery />
    @include('components.areas-we-serve')
    @include('components.testimonials')
    @include('components.pricing')
    @include('components.faq')
    @include('components.blog-teaser')
    @include('components.final-cta')
{{--    @include('components.cta')--}}
    @include('components.footer')

    @push('scripts')
    <script src="/js/gsap.min.js"></script>
    <script src="/js/gsap-scroll-trigger.min.js"></script>
    <script src="/js/gsap-split-text.min.js"></script>
    <script>
        try {
            gsap.registerPlugin(ScrollTrigger, SplitText);
        } catch (e) {}

        document.addEventListener('DOMContentLoaded', function () {
            try {
                var tl = gsap.timeline({ defaults: { ease: 'power3.out' } });

                var badge = document.getElementById('hero-badge');
                if (badge) {
                    tl.from(badge, { y: -30, opacity: 0, duration: 0.6 });
                }

                var headline = document.getElementById('hero-headline');
                if (headline && typeof SplitText !== 'undefined') {
                    var split = new SplitText(headline, { type: 'words' });
                    tl.from(split.words, { y: 60, opacity: 0, duration: 0.7, stagger: 0.08 }, '-=0.3');
                } else if (headline) {
                    tl.from(headline, { y: 60, opacity: 0, duration: 0.7 }, '-=0.3');
                }

                var sub = document.getElementById('hero-sub');
                if (sub) { tl.from(sub, { y: 30, opacity: 0, duration: 0.6 }, '-=0.4'); }

                var trust = document.getElementById('hero-trust');
                if (trust) { tl.from(trust, { y: 20, opacity: 0, duration: 0.5 }, '-=0.3'); }

                var form = document.getElementById('hero-form');
                if (form) { tl.from(form, { x: 50, opacity: 0, duration: 0.8 }, '-=0.6'); }
            } catch (e) {}
        });
    </script>
    @endpush
@endsection
