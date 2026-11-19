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

        <div class="max-lg:hidden" data-layout="desktop">@include('components.navbar')</div>
        <div class="max-lg:hidden" data-layout="desktop">@include('components.hero')</div>
        <div class="lg:hidden" data-layout="phone">@include('components.app-home.top')</div>

        <div class="max-lg:hidden" data-layout="desktop" data-lazy>
            @include('components.marquee')
            @include('components.who-we-help')
        </div>
        <div data-lazy>@include('components.services')</div>
        <div class="max-lg:hidden" data-layout="desktop" data-lazy>
            @include('components.how-it-works')
            @include('components.why-us')
            @include('components.testimonials')
        </div>
        <div data-lazy><x-before-after-gallery /></div>
        <div data-lazy>@include('components.pricing')</div>
        <div data-lazy>@include('components.areas-we-serve')</div>
        <div data-lazy>@include('components.faq')</div>
        <div class="max-lg:hidden" data-layout="desktop" data-lazy>
            @include('components.blog-teaser')
            @include('components.final-cta')
        </div>
        {{--    @include('components.cta')--}}
        <div class="max-lg:pb-20" data-lazy>@include('components.footer')</div>

        <div class="lg:hidden" data-layout="phone">@include('components.app-home.chrome')</div>
    </div>

    {{-- Keep Alpine's start-up short (PageSpeed "Total Blocking Time"):
         - the layout that isn't on screen is skipped (x-ignore), so a computer doesn't set up the phone booking sheet
           and a phone doesn't set up the desktop card; if the screen crosses 1024px later (a tablet rotated), the
           other layout starts then;
         - sections further down ([data-lazy]) start when they come within about a screen of view, or straight away
           when someone jumps to them.
         Runs while the page is parsed, before Alpine (loaded deferred) starts. --}}
    <script>
        (function () {
            var wide = window.matchMedia('(min-width: 1024px)');
            var hidden = function () { return wide.matches ? 'phone' : 'desktop'; };
            var start = function (el) {
                if (!el.hasAttribute('x-ignore')) return;
                el.removeAttribute('x-ignore');
                // Alpine also marks skipped elements internally; clear that too or initTree skips it again.
                delete el._x_ignore; delete el._x_ignoreSelf;
                if (window.Alpine && document.body._x_alpineStarted) window.Alpine.initTree(el);
            };
            document.addEventListener('alpine:initialized', function () { document.body._x_alpineStarted = true; });
            var io = 'IntersectionObserver' in window ? new IntersectionObserver(function (entries) {
                entries.forEach(function (e) { if (e.isIntersecting) { io.unobserve(e.target); start(e.target); } });
            }, { rootMargin: '900px 0px' }) : null;
            var lazy = function (el) { if (!io) return; el.setAttribute('x-ignore', ''); io.observe(el); };

            document.querySelectorAll('[data-layout="' + hidden() + '"]').forEach(function (el) { el.setAttribute('x-ignore', ''); });
            document.querySelectorAll('[data-lazy]').forEach(function (el) {
                if (el.getAttribute('data-layout') !== hidden()) lazy(el);
            });
            wide.addEventListener('change', function () {
                document.querySelectorAll('[data-layout]:not([data-layout="' + hidden() + '"])[x-ignore]').forEach(function (el) {
                    if (el.hasAttribute('data-lazy')) io ? io.observe(el) : start(el); else start(el);
                });
            });
        })();
    </script>

@endsection
