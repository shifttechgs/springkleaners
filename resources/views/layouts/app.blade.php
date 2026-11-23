<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Google tag (gtag.js): calls queue immediately; the library loads on first interaction or 6s after page load, off the critical path -->
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-V00EMDHD3D');
      (function () {
        var loaded = false;
        function loadGtag() {
          if (loaded) return;
          loaded = true;
          var s = document.createElement('script');
          s.async = true;
          s.src = 'https://www.googletagmanager.com/gtag/js?id=G-V00EMDHD3D';
          document.head.appendChild(s);
        }
        ['pointerdown', 'keydown', 'scroll', 'touchstart'].forEach(function (ev) {
          window.addEventListener(ev, loadGtag, { once: true, passive: true });
        });
        window.addEventListener('load', function () { setTimeout(loadGtag, 6000); });
      })();
    </script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('description', 'Insured, background-checked cleaners for homes, rentals and offices across the Cape Town Northern Suburbs and Blouberg. See your price and book online.')">
    <meta name="robots" content="@yield('robots', 'index, follow')">
    <title>@yield('title', 'SpringKleaners | Cleaning Services, Cape Town Northern Suburbs')</title>
    <link rel="canonical" href="{{ url()->current() }}">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/fav.png">
    <link rel="icon" type="image/png" sizes="512x512" href="/images/fav-512.png">
    <link rel="apple-touch-icon" href="/images/fav-180.png">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="SpringKleaners">
    <meta property="og:locale" content="en_ZA">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'SpringKleaners | Cleaning Services, Cape Town Northern Suburbs')">
    <meta property="og:description" content="@yield('description', 'Insured, background-checked cleaners for homes, rentals and offices across the Cape Town Northern Suburbs and Blouberg. See your price and book online.')">
    {{-- Link previews (WhatsApp, Facebook, LinkedIn, X): 1200x630 JPG cards from public/images/og, made by
         `npm run og-images`. JPG on purpose: WhatsApp and Facebook don't show AVIF/WebP previews. Pages can set
         their own with @section('og_image') / @section('og_image_alt'). --}}
    <meta property="og:image" content="@yield('og_image', url('/images/og/default.jpg'))">
    <meta property="og:image:secure_url" content="@yield('og_image', url('/images/og/default.jpg'))">
    <meta property="og:image:type" content="image/jpeg">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="@yield('og_image_alt', 'Two SpringKleaners team members cleaning a bathroom vanity in Cape Town')">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'SpringKleaners | Cleaning Services, Cape Town Northern Suburbs')">
    <meta name="twitter:description" content="@yield('description', 'Insured, background-checked cleaners for homes, rentals and offices across the Cape Town Northern Suburbs and Blouberg. See your price and book online.')">
    <meta name="twitter:image" content="@yield('og_image', url('/images/og/default.jpg'))">
    <meta name="twitter:image:alt" content="@yield('og_image_alt', 'Two SpringKleaners team members cleaning a bathroom vanity in Cape Town')">

    @php
        $localBusinessLd = [
            '@context' => 'https://schema.org',
            '@type' => 'HousekeepingService',
            '@id' => rtrim(config('app.url'), '/').'/#business',
            'name' => 'SpringKleaners',
            'alternateName' => 'Spring Kleaners',
            'legalName' => 'SpringKleaners (Reg. 2021/363748/07)',
            'url' => config('app.url'),
            'logo' => url('/images/logo.png'),
            'telephone' => '+27815274711',
            'email' => 'bookings@springkleaners.co.za',
            'image' => url('/images/works/1.png'),
            'priceRange' => 'R750 - R1800',
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => '1 Stepney Rd',
                'addressLocality' => 'Parklands',
                'addressRegion' => 'Western Cape',
                'postalCode' => '7441',
                'addressCountry' => 'ZA',
            ],
            'geo' => [
                '@type' => 'GeoCoordinates',
                'latitude' => '-33.8038708',
                'longitude' => '18.5154524',
            ],
            'sameAs' => [
                'https://share.google/fP58DLAFT2V3FyTUK',
            ],
            'areaServed' => collect(config('locations'))->map(fn ($loc) => [
                '@type' => 'Place',
                'name' => $loc['name'],
            ])->push(['@type' => 'City', 'name' => 'Cape Town'])->values()->all(),
            'aggregateRating' => [
                '@type' => 'AggregateRating',
                'ratingValue' => '4.9',
                'reviewCount' => '10',
            ],
        ];
    @endphp
    <script type="application/ld+json">{!! json_encode($localBusinessLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>

    <link rel="preload" href="/fonts/plus-jakarta-sans-latin.woff2" as="font" type="font/woff2" crossorigin>
    @stack('preload')

    {{-- Non-critical CSS: loaded without blocking first paint --}}
    <link rel="preload" href="/css/animate.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="/css/odometer-theme-default.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="/css/animate.min.css">
        <link rel="stylesheet" href="/css/odometer-theme-default.css">
    </noscript>

    @vite(['resources/css/app.css'])
    <script defer src="/js/alpine-3.14.9.min.js"></script>

    @stack('styles')
</head>
<body class="bg-white text-[#081d3a] antialiased">

    @yield('content')

    <div class="mobile-bar fixed bottom-3 inset-x-3 z-50 lg:hidden flex gap-2" data-mobile-bar>
        <a href="{{ \App\Support\WhatsApp::link(trim(html_entity_decode($__env->yieldContent('whatsapp_message'), ENT_QUOTES)) ?: null) }}"
           target="_blank"
           rel="noopener noreferrer"
           class="flex-1 flex items-center justify-center gap-2 rounded-full bg-[#25d366] text-[#081d3a] font-bold py-3.5 text-[14px] tracking-tight shadow-[0_8px_24px_-6px_rgba(0,0,0,0.5)]">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
            WhatsApp Us
        </a>
        <a href="@yield('book_url', route('booking.show'))" data-mobile-bar-quote
           class="flex-1 flex items-center justify-center gap-2 rounded-full bg-[#f6e304] text-[#081d3a] font-bold py-3.5 text-[14px] tracking-tight shadow-[0_8px_24px_-6px_rgba(0,0,0,0.5)]">
            Get My Instant Quote
        </a>
    </div>

    <script defer src="/js/wow.min.js"></script>
    <script defer src="/js/odometer.min.js"></script>

    <script>
        // Scroll reveals only animate sections further down, so they start once the browser is idle: run together
        // with Alpine's start-up they made one long task that blocked the page (PageSpeed "Total Blocking Time").
        document.addEventListener('DOMContentLoaded', function () {
            var start = function () { if (typeof WOW !== 'undefined') new WOW({ offset: 80, mobile: true }).init(); };
            if ('requestIdleCallback' in window) requestIdleCallback(start, { timeout: 1000 }); else setTimeout(start, 200);
        });

        (function () {
            var nav = document.getElementById('mainNav');
            function sync() { if (nav) nav.classList.toggle('nav-scrolled', window.scrollY > 80); }
            window.addEventListener('scroll', sync, { passive: true });
            // First check on the first frame, not right away: reading the scroll position while the page is still
            // loading forces a full layout mid-parse (~100-200 ms of blocking on the homepage).
            requestAnimationFrame(sync);
        })();

        // Phones: the floating WhatsApp/Quote bar steps aside while it would get in the way — when a quote form
        // marked [data-hide-mobile-bar] is on screen (it would cover the fields and the Book button) and while
        // someone is typing (on Android it would ride up on top of the keyboard). Where there is such a form,
        // its Quote button jumps back to it instead of leaving the page.
        (function () {
            var bar = document.querySelector('[data-mobile-bar]');
            if (!bar) return;
            var forms = document.querySelectorAll('[data-hide-mobile-bar]');
            var visible = 0, typing = false;
            function sync() { bar.classList.toggle('is-hidden', visible > 0 || typing); }
            if (forms.length) {
                var quote = bar.querySelector('[data-mobile-bar-quote]');
                if (quote && forms[0].id) quote.setAttribute('href', '#' + forms[0].id);
                if ('IntersectionObserver' in window) {
                    var io = new IntersectionObserver(function (entries) {
                        entries.forEach(function (e) {
                            var on = e.isIntersecting && e.intersectionRect.height > window.innerHeight * 0.45;
                            if (on !== !!e.target._barOn) { e.target._barOn = on; visible += on ? 1 : -1; }
                        });
                        sync();
                    }, { threshold: [0, 0.1, 0.25, 0.5, 0.75, 1] });
                    forms.forEach(function (f) { io.observe(f); });
                }
            }
            var textual = /^(text|tel|email|number|search|url|password|date|)$/;
            document.addEventListener('focusin', function (e) {
                var t = e.target;
                typing = t.tagName === 'TEXTAREA' || t.tagName === 'SELECT' || (t.tagName === 'INPUT' && textual.test(t.type || ''));
                sync();
            });
            document.addEventListener('focusout', function () {
                setTimeout(function () { if (!document.activeElement || document.activeElement === document.body) { typing = false; sync(); } }, 50);
            });
        })();

        document.addEventListener('DOMContentLoaded', function () {
            var targets = document.querySelectorAll('[data-odometer]');
            if (!targets.length) return;
            var observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        var el = entry.target;
                        var val = parseInt(el.getAttribute('data-odometer'), 10);
                        if (typeof Odometer !== 'undefined') {
                            var od = new Odometer({ el: el, value: 0 });
                            od.update(val);
                        } else {
                            el.textContent = val;
                        }
                        observer.unobserve(el);
                    }
                });
            }, { threshold: 0.3 });
            targets.forEach(function (t) { observer.observe(t); });
        });
    </script>

    @stack('scripts')
</body>
</html>
