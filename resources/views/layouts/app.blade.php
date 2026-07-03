<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('description', 'SpringKleaners — Premium deep cleaning, end-of-tenancy and post-construction cleaning services across Cape Town\'s Northern Suburbs. Fully insured, vetted staff, free inspection.')">
    <title>@yield('title', 'SpringKleaners | Premium Cleaning — Cape Town Northern Suburbs')</title>
    <link rel="canonical" href="{{ url()->current() }}">
    <link rel="icon" type="image/png" href="/images/fav.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Geist:wght@100..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="/css/animate.min.css">
    <link rel="stylesheet" href="/css/swiper.min.css">
    <link rel="stylesheet" href="/css/odometer-theme-default.css">
    <link rel="stylesheet" href="/css/font-awesome-pro.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style type="text/tailwindcss">
        @layer base {
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
            }
        }
        @layer utilities {
            .text-navy { color: #081d3a; }
            .bg-navy { background-color: #081d3a; }
            .bg-navy-deep { background-color: #040f1f; }
            .text-gold { color: #f6e304; }
            .bg-gold { background-color: #f6e304; }
            .border-gold { border-color: #f6e304; }
            .text-muted { color: #647082; }
            .bg-light { background-color: #f8f9fc; }
            .btn-gold {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 1rem 1.75rem;
                background-color: #f6e304;
                color: #081d3a;
                font-weight: 700;
                border-radius: 0.75rem;
                transition: all 0.2s;
                box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
                font-size: 15px;
                letter-spacing: -0.025em;
            }
            .btn-gold:hover {
                background-color: #fef08a;
                box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
            }
            .btn-gold:active { transform: scale(0.95); }
            .btn-outline {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 1rem 1.75rem;
                background-color: transparent;
                color: white;
                font-weight: 600;
                border-radius: 0.75rem;
                border: 2px solid rgba(255,255,255,0.3);
                transition: all 0.2s;
                font-size: 15px;
                letter-spacing: -0.025em;
            }
            .btn-outline:hover {
                border-color: #f6e304;
                color: #f6e304;
            }
            .btn-outline:active { transform: scale(0.95); }
            .section-wrap {
                max-width: 80rem;
                margin-left: auto;
                margin-right: auto;
                padding-left: 1.25rem;
                padding-right: 1.25rem;
            }
            @media (min-width: 640px) {
                .section-wrap { padding-left: 2rem; padding-right: 2rem; }
            }
            @media (min-width: 1024px) {
                .section-wrap { padding-left: 3rem; padding-right: 3rem; }
            }
            .section-py { padding-top: 5rem; padding-bottom: 5rem; }
            @media (min-width: 1024px) {
                .section-py { padding-top: 7rem; padding-bottom: 7rem; }
            }
        }
    </style>

    <style>
        @keyframes marquee-scroll {
            from { transform: translateX(0); }
            to { transform: translateX(-50%); }
        }
        .marquee-track {
            animation: marquee-scroll 60s linear infinite;
            display: flex;
            width: max-content;
        }
        .marquee-track:hover { animation-play-state: paused; }
        .marquee-track-lg { animation-duration: 45s; }
        .marquee-track-reverse { animation-direction: reverse; }

        @keyframes review-scroll-y {
            from { transform: translateY(0); }
            to { transform: translateY(-50%); }
        }
        .review-scroll { animation: review-scroll-y 32s linear infinite; }
        .review-scroll-down { animation-direction: reverse; }
        .review-scroll:hover { animation-play-state: paused; }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #040f1f; }
        ::-webkit-scrollbar-thumb { background: #081d3a; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #f6e304; }

        .odometer { font-family: 'Plus Jakarta Sans', sans-serif; }

        #mainNav { transition: all 0.4s ease; }
        #mainNav.nav-scrolled > div:first-child {
            box-shadow: 0 8px 40px rgba(0,0,0,0.35);
        }

        .wow { visibility: hidden; }
        [x-cloak] { display: none !important; }

        .swiper-pagination-bullet { background: #647082 !important; opacity: 1 !important; }
        .swiper-pagination-bullet-active {
            background: #f6e304 !important;
            width: 28px !important;
            border-radius: 4px !important;
            transition: width 0.3s;
        }

        .hero-pattern {
            background-image: url('/images/pattern-2.svg');
            background-repeat: repeat;
            background-size: 300px;
            opacity: 0.04;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #f6e304 !important;
        }

        .service-card:hover { transform: translateY(-6px); }

        .step-connector {
            position: absolute;
            top: 28px;
            left: calc(50% + 32px);
            right: calc(-50% + 32px);
            height: 1px;
            background: linear-gradient(90deg, #f6e304, rgba(246,227,4,0.2));
        }
    </style>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: '#081d3a',
                        'navy-deep': '#040f1f',
                        gold: '#f6e304',
                        muted: '#647082',
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>

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

    <script src="/js/jquery.min.js"></script>
    <script src="/js/gsap.min.js"></script>
    <script src="/js/gsap-scroll-trigger.min.js"></script>
    <script src="/js/gsap-split-text.min.js"></script>
    <script src="/js/wow.min.js"></script>
    <script src="/js/swiper.min.js"></script>
    <script src="/js/odometer.min.js"></script>
    <script src="/js/appear.min.js"></script>

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

            try {
                if (typeof Swiper !== 'undefined' && document.getElementById('testimonialSwiper')) {
                    new Swiper('#testimonialSwiper', {
                        loop: true,
                        autoplay: { delay: 5000, disableOnInteraction: false },
                        pagination: { el: '.swiper-pagination', clickable: true },
                        navigation: { nextEl: '.swiper-btn-next', prevEl: '.swiper-btn-prev' },
                        spaceBetween: 24,
                        slidesPerView: 1,
                        breakpoints: {
                            640: { slidesPerView: 2 },
                            1024: { slidesPerView: 3 }
                        }
                    });
                }
            } catch (e) {}
        });

        if (typeof $ !== 'undefined' && typeof $.fn !== 'undefined' && typeof $.fn.appear !== 'undefined') {
            $('[data-odometer]').appear(function () {
                var el = $(this);
                var val = el.data('odometer');
                var od = el[0];
                if (od && typeof Odometer !== 'undefined') {
                    var odInstance = new Odometer({ el: od, value: 0 });
                    odInstance.update(val);
                }
            });
        } else {
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
        }
    </script>

    @stack('scripts')
</body>
</html>
