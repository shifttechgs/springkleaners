@php
    $serviceAreas = collect(config('locations'))->pluck('name')->all();
@endphp
{{-- Slim proof strip under the hero: the rating, then a slow ticker of areas. The full reviews live in the testimonials section. --}}
<section class="bg-[#040f1f] border-y border-white/10 py-8 overflow-hidden">

    <div class="text-center mb-6 px-5">
        <span class="text-[#f6e304] text-lg tracking-tight">★★★★★</span>
        <span class="text-white font-bold text-lg ml-2">4.9<span class="text-white/70 font-normal">/5</span></span>
        <span class="text-white/75 text-[15px]"> · Rated by Cape Town clients</span>
    </div>

    <div class="relative flex" aria-hidden="true">
        <div class="marquee-track marquee-track-reverse flex items-center gap-0">
            @for ($i = 0; $i < 2; $i++)
                @foreach ($serviceAreas as $area)
                    <span class="text-white/70 text-[18px] lg:text-[22px] font-medium flex-shrink-0 px-6 lg:px-8">{{ $area }}</span>
                    <span class="text-[#f6e304] text-[18px] lg:text-[22px] flex-shrink-0">•</span>
                @endforeach
            @endfor
        </div>
    </div>

</section>
