<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('description', 'SpringKleaners — Premium deep cleaning, end-of-tenancy and post-construction cleaning services across Cape Town\'s Northern Suburbs. Fully insured, vetted staff, free inspection.')">
    <meta name="robots" content="@yield('robots', 'index, follow')">
    <title>@yield('title', 'SpringKleaners | Premium Cleaning — Cape Town Northern Suburbs')</title>
    <link rel="canonical" href="{{ url()->current() }}">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/fav.png">
    <link rel="icon" type="image/png" sizes="512x512" href="/images/fav-512.png">
    <link rel="apple-touch-icon" href="/images/fav-180.png">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="SpringKleaners">
    <meta property="og:locale" content="en_ZA">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'SpringKleaners | Premium Cleaning — Cape Town Northern Suburbs')">
    <meta property="og:description" content="@yield('description', 'SpringKleaners — Premium deep cleaning, end-of-tenancy and post-construction cleaning services across Cape Town\'s Northern Suburbs. Fully insured, vetted staff, free inspection.')">
    <meta property="og:image" content="@yield('og_image', url('/images/works/hero/hero2_cleaning.avif'))">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'SpringKleaners | Premium Cleaning — Cape Town Northern Suburbs')">
    <meta name="twitter:description" content="@yield('description', 'SpringKleaners — Premium deep cleaning, end-of-tenancy and post-construction cleaning services across Cape Town\'s Northern Suburbs. Fully insured, vetted staff, free inspection.')">
    <meta name="twitter:image" content="@yield('og_image', url('/images/works/hero/hero2_cleaning.avif'))">

    @php
        $localBusinessLd = [
            '@context' => 'https://schema.org',
            '@type' => 'HousekeepingService',
            'name' => 'SpringKleaners',
            'url' => config('app.url'),
            'telephone' => '+27815274711',
            'email' => 'bookings@springkleaners.co.za',
            'image' => url('/images/works/1.png'),
            'priceRange' => 'R950 - R1800',
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => '1 Stepney Rd',
                'addressLocality' => 'Parklands',
                'addressRegion' => 'Western Cape',
                'addressCountry' => 'ZA',
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

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Geist:wght@100..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="/css/animate.min.css">
    <link rel="stylesheet" href="/css/odometer-theme-default.css">
    <link rel="stylesheet" href="/css/font-awesome-pro.min.css">

    @vite(['resources/css/app.css'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('styles')
</head>
<body class="bg-white text-[#081d3a] antialiased">

    @yield('content')

    <div class="fixed bottom-0 left-0 right-0 z-50 lg:hidden flex border-t border-white/10 shadow-2xl">
        <a href="https://wa.me/27815274711"
           target="_blank"
           rel="noopener noreferrer"
           class="flex-1 flex items-center justify-center gap-2 bg-[#25d366] text-white font-bold py-4 text-[14px] tracking-tight">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
            WhatsApp Us
        </a>
        <a href="{{ route('booking.show') }}"
           class="flex-1 flex items-center justify-center gap-2 bg-[#f6e304] text-[#081d3a] font-bold py-4 text-[14px] tracking-tight">
            Get My Instant Quote
        </a>
    </div>

    <script src="/js/wow.min.js"></script>
    <script src="/js/odometer.min.js"></script>

    <script>
        new WOW({ offset: 80, mobile: true }).init();

        window.addEventListener('scroll', function () {
            var nav = document.getElementById('mainNav');
            if (nav) {
                if (window.scrollY > 80) {
                    nav.classList.add('nav-scrolled');
                } else {
                    nav.classList.remove('nav-scrolled');
                }
            }
        });

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
