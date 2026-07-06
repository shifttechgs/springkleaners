@extends('layouts.app')
@section('title', 'SpringKleaners | Premium Cleaning — Cape Town Northern Suburbs')
@section('content')
    @include('components.navbar')
    @include('components.hero')
    @include('components.marquee')
    @include('components.who-we-help')
    @include('components.services')
    @include('components.how-it-works')
    @include('components.why-us')
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
