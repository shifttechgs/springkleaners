@push('preload')
<link rel="preload" href="/fonts/geist-600.woff2" as="font" type="font/woff2" crossorigin>
@endpush
@php
    // Hero slides: background photos that change with a striped reveal (see the hero slider script at the end).
    // `dim` is how much of the photo shows through on desktop, `dimMobile` on phones (lower = darker, for text contrast).
    // Set `enabled` to false to hide a slide. With one slide left, the counter, thumbnails and pause button are hidden too.
    $heroSlides = array_values(array_filter([
        ['enabled' => true, 'name' => 'slide-kitchen', 'label' => 'Deep Cleaning', 'alt' => 'A spotless, deep-cleaned kitchen with a navy island', 'w' => 2560, 'h' => 1706, 'pos' => '40% center', 'dim' => 0.44, 'dimMobile' => 0.42],
        ['enabled' => false, 'name' => 'slide-movein', 'label' => 'Move-in Ready', 'alt' => 'A bright, move-in ready kitchen with white cabinets and a grey island', 'w' => 2560, 'h' => 1707, 'pos' => '45% center', 'dim' => 0.4, 'dimMobile' => 0.38],
        ['enabled' => false, 'name' => 'slide-postcon', 'label' => 'Post Construction', 'alt' => 'A mop bucket, broom and ladder outside a newly built house, ready for the post-construction clean', 'w' => 2560, 'h' => 1707, 'pos' => '60% center', 'dim' => 0.36, 'dimMobile' => 0.34],
    ], fn (array $slide) => $slide['enabled']));
    $heroHasSlideshow = count($heroSlides) > 1;
@endphp
{{-- overflow: clip (with overflow-hidden as the fallback) so the zooming background can never make the hero
     scroll sideways inside itself — e.g. when a field in the quote card gets focus. --}}
<section class="hero-compact relative isolate flex items-end min-h-[100svh] overflow-hidden bg-[#17171a] pt-28 pb-10 lg:pt-32 lg:pb-16" style="overflow: clip;">

    {{-- Phones see the app hero instead (welcome.blade.php hides this one below 1024px); sizes="1px" there makes the
         browser pick the smallest file, the same one the app hero shows, so phones don't download a big hidden photo. --}}
    {{-- Background slides. Each photo fades into the near-black base (mask on .hero-photo) and drifts slowly while on
         screen; the next one arrives through horizontal stripes. Decorative, so hidden from screen readers. --}}
    <div class="absolute inset-x-0 top-0 -z-30 h-[100svh] lg:inset-0 lg:h-full" data-hero-slider aria-hidden="true">
        @foreach ($heroSlides as $i => $slide)
            <div class="hero-slide {{ $i === 0 ? 'is-on' : '' }}" style="--dim: {{ $slide['dim'] }}; --dim-m: {{ $slide['dimMobile'] }};" data-label="{{ $slide['label'] }}">
                <div class="hero-slide-zoom">
                    <img src="/images/works/hero/{{ $slide['name'] }}-1280.avif"
                         srcset="/images/works/hero/{{ $slide['name'] }}-828.avif 828w, /images/works/hero/{{ $slide['name'] }}-1280.avif 1280w, /images/works/hero/{{ $slide['name'] }}-1920.avif 1920w, /images/works/hero/{{ $slide['name'] }}-2560.avif 2560w"
                         sizes="(max-width: 1023px) 1px, 100vw"
                         width="{{ $slide['w'] }}" height="{{ $slide['h'] }}"
                         fetchpriority="{{ $i === 0 ? 'high' : 'low' }}" decoding="async"
                         alt="{{ $slide['alt'] }}"
                         class="hero-photo h-full w-full object-cover" style="object-position: {{ $slide['pos'] }};">
                </div>
            </div>
        @endforeach
        {{-- Desktop: a plain linear fade (no glow, no spotlight) that darkens only the lower-left, where the white
             text sits, so the bright kitchen stays bright at the top right. --}}
        <div class="hero-scrim" aria-hidden="true"></div>
    </div>

    <div class="section-wrap w-full">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 xl:gap-16 items-end">

            <div class="lg:col-span-7 lg:pb-3">
                {{-- Proof first: the rating and the number of cleans sit right above the headline, where they're read. --}}
                <p id="hero-badge" class="hero-settle mb-7 flex flex-wrap items-center gap-x-2.5 sm:gap-x-3 gap-y-1.5 text-[13px] sm:text-[14px] font-medium text-white">
                    <svg class="h-[13px] w-[72px] sm:h-4 sm:w-[88px] flex-shrink-0 text-[#f6e304]" viewBox="0 0 88 16" fill="currentColor" role="img" aria-label="Rated 4.9 out of 5">
                        <defs><path id="hero-star" d="M8 .6l2.2 4.9 5.3.5-4 3.6 1.2 5.3L8 12.2 3.3 14.9l1.2-5.3-4-3.6 5.3-.5z"/></defs>
                        <use href="#hero-star"/><use href="#hero-star" x="18"/><use href="#hero-star" x="36"/><use href="#hero-star" x="54"/><use href="#hero-star" x="72"/>
                    </svg>
                    <span><span class="font-bold">4.9</span> on Google</span>
                    <span class="h-3.5 w-px bg-white/40" aria-hidden="true"></span>
                    <span><span class="font-bold">500+</span> cleans done</span>
                </p>

                <h1 id="hero-headline" class="font-display text-white text-[40px] sm:text-[58px] lg:text-[62px] xl:text-[76px] 2xl:text-[84px] min-[2400px]:text-[100px] leading-[1.04] mb-8">
                    <span class="hero-line"><span>Cape Town's</span></span>
                    <span class="hero-line"><span>Northern Suburbs,</span></span>
                    <span class="hero-line"><span>cleaned properly.</span></span>
                </h1>

                <p id="hero-sub" class="hero-settle max-w-[34rem] lg:max-w-[38rem] 2xl:max-w-[44rem] text-[17px] lg:text-[19px] 2xl:text-[21px] leading-relaxed text-white/90 mb-9 lg:mb-0">
                    Deep cleaning, end-of-tenancy and post-construction cleaning for homes and businesses, with your price before you book.
                </p>

                <div class="hero-settle flex flex-wrap items-center gap-x-6 gap-y-3 mb-10 lg:hidden">
                    <x-arrow-button href="#hero-form">Get my instant quote</x-arrow-button>
                    <a href="#services" class="link-chip link-chip--down text-[15px] font-semibold text-white">See our services<span class="btn-arrow__chip link-chip__chip" aria-hidden="true"><svg class="btn-arrow__icon btn-arrow__icon--out" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M6 13l6 6 6-6"/></svg><svg class="btn-arrow__icon btn-arrow__icon--in" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M6 13l6 6 6-6"/></svg></span></a>
                </div>
            </div>

            <div class="lg:col-span-5 scroll-mt-24 lg:scroll-mt-0" id="hero-form" data-hide-mobile-bar>
                @include('components.quote-form')
            </div>
        </div>

        {{-- Fact row: plain facts under a hairline, the way editorial heroes close. Text only, no icons or chips.
             Every claim here is stated elsewhere on the site (about page, reviews) — keep it that way. Shown when
             there is a single background photo; with 2+ slides the slide strip below takes this spot instead. --}}
        @if (! $heroHasSlideshow)
        <div class="hero-facts hero-settle mt-8 lg:mt-10 border-t border-white/15 pt-4 text-[12.5px] sm:text-[13px] text-white/85">
            <ul class="grid grid-cols-3 items-center lg:flex lg:whitespace-nowrap">
                <li class="pr-2 sm:pr-5 lg:pl-0"><span class="lg:hidden">Vetted &amp; insured</span><span class="hidden lg:inline">Background-checked, insured team</span></li>
                <li class="border-l border-white/25 px-2 sm:px-5 text-center lg:text-left"><span class="lg:hidden">Weekends</span><span class="hidden lg:inline">Saturday &amp; Sunday bookings</span></li>
                <li class="border-l border-white/25 px-2 sm:px-5 text-center lg:text-left">No upfront payment</li>
                <li class="hidden lg:block lg:ml-auto">
                    <a href="#services" class="link-chip link-chip--down link-chip--sm font-semibold text-white">Our services<span class="btn-arrow__chip link-chip__chip" aria-hidden="true"><svg class="btn-arrow__icon btn-arrow__icon--out" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M6 13l6 6 6-6"/></svg><svg class="btn-arrow__icon btn-arrow__icon--in" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M6 13l6 6 6-6"/></svg></span></a>
                </li>
            </ul>
        </div>
        @endif

        {{-- Slide label + counter, full width under the copy and the form. The line between the numbers fills while
             a slide is on screen and drives the autoplay. Only shown with 2+ slides: with one, it's just a label. --}}
        @if ($heroHasSlideshow)
        <div class="hero-strip hero-settle mt-8 lg:mt-10 flex items-center gap-3 sm:gap-4 whitespace-nowrap border-t border-white/15 pt-4 text-[13px] text-white/80">
            <span class="hidden min-[400px]:inline font-medium text-white">Cape Town</span>
            <span class="hidden min-[400px]:inline h-3.5 w-px bg-white/35" aria-hidden="true"></span>
            <span class="hero-roll" data-hero-label>{{ $heroSlides[0]['label'] }}</span>
            <span class="ml-2 sm:ml-4 flex items-center gap-3 tabular-nums" aria-hidden="true">
                <span data-hero-count>01</span>
                <span class="relative h-px w-12 overflow-hidden bg-white/25"><span class="hero-progress" data-hero-progress></span></span>
                <span>{{ str_pad(count($heroSlides), 2, '0', STR_PAD_LEFT) }}</span>
            </span>
            <span class="ml-auto flex items-center gap-3">
                @foreach ($heroSlides as $i => $slide)
                    <button type="button" class="hero-thumb hidden lg:flex {{ $i === 0 ? 'is-active' : '' }}" data-hero-thumb="{{ $i }}" aria-label="Show slide {{ $i + 1 }}: {{ $slide['label'] }}" @if ($i === 0) aria-current="true" @endif>
                        <img src="/images/works/hero/{{ $slide['name'] }}-828.avif" alt="{{ $slide['alt'] }}" width="80" height="52" loading="lazy" decoding="async" class="hero-thumb__img">
                    </button>
                @endforeach
                <button type="button" class="hero-pause" data-hero-pause aria-label="Pause background slideshow" aria-pressed="false">
                    <svg class="hero-pause__icon-pause h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M7 5h3v14H7zM14 5h3v14h-3z"/></svg>
                    <svg class="hero-pause__icon-play h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg>
                </button>
            </span>
        </div>
        @endif
    </div>
</section>

@push('scripts')
<script>
    // Hero slider: the incoming photo is revealed through horizontal stripes that open from the bottom up
    // (a mask redrawn each frame), and the progress line under the counter decides when to move on.
    (function () {
        var root = document.querySelector('[data-hero-slider]');
        if (!root) return;
        var slides = Array.prototype.slice.call(root.querySelectorAll('.hero-slide'));
        if (slides.length < 2) return;

        var thumbs = Array.prototype.slice.call(document.querySelectorAll('[data-hero-thumb]'));
        var pauses = Array.prototype.slice.call(document.querySelectorAll('[data-hero-pause]'));
        var label = document.querySelector('[data-hero-label]');
        var count = document.querySelector('[data-hero-count]');
        var progress = document.querySelector('[data-hero-progress]');
        var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        var BANDS = 30, REVEAL_MS = 1400;
        var index = 0, busy = false, paused = reduce;

        function stripes(t) {
            var size = 100 / BANDS, parts = [];
            for (var i = 0; i < BANDS; i++) {
                var start = (i / BANDS) * 0.55;
                var local = Math.min(1, Math.max(0, (t - start) / 0.45));
                var eased = 1 - Math.pow(1 - local, 3);
                var a = i * size, b = a + size * eased;
                parts.push('#000 ' + a + '%', '#000 ' + b + '%', 'transparent ' + b + '%', 'transparent ' + (a + size) + '%');
            }
            return 'linear-gradient(0deg, ' + parts.join(', ') + ')';
        }

        function setMask(el, value) {
            el.style.webkitMaskImage = value;
            el.style.maskImage = value;
        }

        function restartProgress() {
            if (!progress) return;
            progress.classList.remove('is-running');
            void progress.offsetWidth;
            progress.classList.add('is-running');
        }

        function updateUi() {
            if (label) {
                label.textContent = slides[index].getAttribute('data-label');
                label.classList.remove('is-rolling');
                void label.offsetWidth;
                label.classList.add('is-rolling');
            }
            if (count) count.textContent = (index + 1 < 10 ? '0' : '') + (index + 1);
            thumbs.forEach(function (t, i) {
                t.classList.toggle('is-active', i === index);
                if (i === index) t.setAttribute('aria-current', 'true'); else t.removeAttribute('aria-current');
            });
            restartProgress();
        }

        function go(next) {
            if (busy || next === index) return;
            var prev = slides[index], incoming = slides[next];
            index = next;
            prev.classList.remove('is-on');
            prev.classList.add('is-prev');
            incoming.classList.add('is-on');
            updateUi();

            if (reduce) {
                prev.classList.remove('is-prev');
                return;
            }
            busy = true;
            var t0 = null;
            setMask(incoming, stripes(0));
            requestAnimationFrame(function frame(now) {
                if (t0 === null) t0 = now;
                var t = Math.min(1, (now - t0) / REVEAL_MS);
                setMask(incoming, stripes(t));
                if (t < 1) return requestAnimationFrame(frame);
                setMask(incoming, '');
                prev.classList.remove('is-prev');
                busy = false;
            });
        }

        function setPaused(value) {
            paused = value;
            root.classList.toggle('is-paused', paused);
            if (progress) progress.classList.toggle('is-paused', paused);
            pauses.forEach(function (b) {
                b.setAttribute('aria-pressed', paused ? 'true' : 'false');
                b.setAttribute('aria-label', paused ? 'Play background slideshow' : 'Pause background slideshow');
                b.classList.toggle('is-paused', paused);
            });
        }

        if (progress) {
            progress.addEventListener('animationend', function () {
                if (!paused) go((index + 1) % slides.length);
            });
        }
        thumbs.forEach(function (t) {
            t.addEventListener('click', function () { go(parseInt(t.getAttribute('data-hero-thumb'), 10)); });
        });
        pauses.forEach(function (b) {
            b.addEventListener('click', function () { setPaused(!paused); });
        });

        setPaused(paused);
        restartProgress();
    })();
</script>
@endpush
