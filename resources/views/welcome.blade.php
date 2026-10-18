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
    @include('components.testimonials')
    <x-before-after-gallery />
    @include('components.pricing')
    @include('components.areas-we-serve')
    @include('components.faq')
    @include('components.blog-teaser')
    @include('components.final-cta')
{{--    @include('components.cta')--}}
    @include('components.footer')

@endsection
