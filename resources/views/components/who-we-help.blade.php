@php
    // The three kinds of space we clean, each leading to the service page that fits it best.
    $spaces = [
        [
            'title' => 'Home',
            'text' => 'Apartments, houses & townhouses — busy homeowners who want their space consistently fresh and spotless.',
            'url' => route('services.show', 'deep-cleaning'),
            'cta' => 'See Deep Cleaning',
        ],
        [
            'title' => 'Workspace',
            'text' => 'Offices, clinics & commercial spaces — maintain a clean, professional environment that supports your team and impresses clients.',
            'url' => route('services.show', 'office-commercial-cleaning'),
            'cta' => 'See Office & Commercial Cleaning',
        ],
        [
            'title' => 'Property',
            'text' => 'New builds, rental turnovers & post-renovation handovers — move-in ready from day one, every time.',
            'url' => route('services.show', 'post-construction'),
            'cta' => 'See Post Construction Cleaning',
        ],
    ];

    // Trust early: two short real reviews, one per column, drifting in opposite directions.
    $reviews = collect(config('reviews'));
    $picked = $reviews->whereIn('name', ['Elizabeth Pierson', 'Mbali Ntshangase'])->values();
    if ($picked->count() < 2) {
        $picked = $reviews->filter(fn ($r) => mb_strlen($r['quote']) <= 120)->take(2)->values();
    }
    $columns = [['up', $picked->take(1)], ['down', $picked->slice(1, 1)->values()]];
    $copies = 3; // repeats so the window never runs out of content
@endphp
<section class="bg-[#f8f9fc] section-py">
    <div class="section-wrap">

        {{-- Header row: the intro on the left; on the right the reviews, with the quote button directly beneath --}}
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-10 mb-14 wow fadeInUp" data-wow-duration="0.7s">
            <div class="max-w-lg">
                <div class="w-8 h-[2px] bg-[#f6e304] mb-3"></div>
                <span class="text-[#081d3a] text-[11px] font-semibold uppercase tracking-[0.18em]">Who We Help</span>
                <h2 class="text-[38px] sm:text-[48px] lg:text-[56px] font-semibold text-[#081d3a] leading-[1.1] tracking-[-0.02em] mt-3 mb-4">
                    Spaces we<br>specialize in
                </h2>
                <p class="text-[#5b6779] text-[15px] leading-relaxed max-w-sm font-normal">
                    We work with busy homeowners, growing businesses, and property owners who need a space that's consistently clean and well cared for.
                </p>
            </div>

            <div class="w-full lg:w-[34rem] lg:flex-shrink-0">
                <div class="review-marquee relative h-[15rem] overflow-hidden rounded-2xl" role="region" aria-label="Customer reviews">
                    <div class="absolute inset-0 grid grid-cols-1 gap-4 md:grid-cols-2">
                        @foreach ($columns as [$direction, $column])
                            <div class="{{ $direction === 'down' ? 'hidden md:block' : '' }} overflow-hidden">
                                <div class="review-scroll {{ $direction === 'down' ? 'review-scroll-down' : '' }} flex flex-col gap-4" style="--review-shift: -{{ 100 / $copies }}%">
                                    @for ($copy = 0; $copy < $copies; $copy++)
                                        @foreach ($column as $r)
                                            <figure class="flex-shrink-0 rounded-2xl border border-gray-100 bg-white p-5" @if ($copy > 0) aria-hidden="true" @endif>
                                                <x-stars class="h-3.5 w-[77px]" />
                                                <blockquote class="mt-3 text-[14px] leading-relaxed text-[#081d3a]/85">&ldquo;{{ $r['quote'] }}&rdquo;</blockquote>
                                                <figcaption class="mt-4 text-[12.5px] leading-snug">
                                                    <span class="block font-semibold text-[#081d3a]">{{ $r['name'] }}</span>
                                                    <span class="block text-[#5b6779]">{{ $r['meta'] }}</span>
                                                </figcaption>
                                            </figure>
                                        @endforeach
                                    @endfor
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="mt-6">
                    <x-arrow-button href="{{ route('booking.show') }}" class="flex-shrink-0">Get My Instant Quote</x-arrow-button>
                </div>
            </div>
        </div>

        {{-- The three spaces as a typographic index, each row leading to its service page --}}
        <ul class="border-b border-[#081d3a]/12 wow fadeInUp" data-wow-duration="0.7s" data-wow-delay="0.15s">
            @foreach ($spaces as $space)
                <li class="border-t border-[#081d3a]/12">
                    <a href="{{ $space['url'] }}" class="group grid grid-cols-[1fr_auto] items-center gap-x-6 gap-y-3 py-7 sm:py-9 lg:grid-cols-12">
                        <span class="block text-[40px] sm:text-[52px] font-semibold leading-none tracking-[-0.02em] text-[#081d3a] transition-transform duration-300 ease-out group-hover:translate-x-2 lg:col-span-5">{{ $space['title'] }}</span>
                        <span class="order-last col-span-2 block max-w-lg text-[14px] leading-relaxed text-[#5b6779] lg:order-none lg:col-span-6 lg:col-start-6">{{ $space['text'] }}</span>
                        <span class="sr-only">{{ $space['cta'] }}</span>
                        <span class="grid h-12 w-12 place-items-center overflow-hidden rounded-full border border-[#081d3a]/20 text-[#081d3a] transition-colors duration-300 group-hover:border-[#f6e304] group-hover:bg-[#f6e304] group-focus-visible:border-[#f6e304] group-focus-visible:bg-[#f6e304] lg:col-span-1 lg:justify-self-end" aria-hidden="true">
                            <svg class="h-5 w-5 transition-transform duration-300 ease-out group-hover:-rotate-45" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </span>
                    </a>
                </li>
            @endforeach
        </ul>

    </div>
</section>
