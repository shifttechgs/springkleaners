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
            'logo' => url('/images/logo.png'),
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

    {{-- One page, one URL for every device. Below 1024px the top of the page is the app layout (tile grid, booking
         sheet, tab bar); computers get the classic hero and sections. Nothing is served differently by device, and
         every section stays in the HTML, so search engines (which index the phone view) still see all the content:
         the phone view hides only sections it replaces with its own version, and shows the content sections
         (services, gallery, pricing, areas, FAQ, footer) in their normal phone layout. --}}
    @include('components.app-home.styles')
    <div x-data="appShell()" x-on:scroll.window.passive="onScroll()"
         x-on:keydown.escape.window="close()" x-on:close-sheet="close()">

        <div class="max-lg:hidden">@include('components.navbar')</div>
        <div class="max-lg:hidden">@include('components.hero')</div>
        <div class="lg:hidden">@include('components.app-home.top')</div>

        <div class="max-lg:hidden">
            @include('components.marquee')
            @include('components.who-we-help')
        </div>
        @include('components.services')
        <div class="max-lg:hidden">
            @include('components.how-it-works')
            @include('components.why-us')
            @include('components.testimonials')
        </div>
        <x-before-after-gallery />
        @include('components.pricing')
        @include('components.areas-we-serve')
        @include('components.faq')
        <div class="max-lg:hidden">
            @include('components.blog-teaser')
            @include('components.final-cta')
        </div>
        {{--    @include('components.cta')--}}
        <div class="max-lg:pb-20">@include('components.footer')</div>

        <div class="lg:hidden">@include('components.app-home.chrome')</div>
    </div>

@endsection
