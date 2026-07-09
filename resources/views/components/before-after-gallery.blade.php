@props(['service' => null])
@php
    $entries = collect(config('before_after'))->filter(function ($entry) use ($service) {
        if ($service === null) {
            return empty($entry['services'] ?? []);
        }

        return in_array($service, $entry['services'] ?? [], true);
    })->values();
@endphp
@if ($entries->isNotEmpty())
<section class="bg-[#f8f9fc] section-py">
    <div class="section-wrap">
        <div class="text-center max-w-2xl mx-auto mb-12 wow fadeInUp" data-wow-duration="0.7s">
            <div class="w-8 h-[2px] bg-[#f6e304] mb-3 mx-auto"></div>
            <span class="text-[#081d3a] text-[11px] font-semibold uppercase tracking-[0.18em]">Before &amp; After</span>
            <h2 class="text-3xl lg:text-4xl font-extrabold text-[#081d3a] tracking-tight leading-tight mt-3">
                See the difference for yourself.
            </h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 wow fadeInUp" data-wow-duration="0.7s" data-wow-delay="0.1s">
            @foreach ($entries as $entry)
            <div class="rounded-2xl overflow-hidden border border-gray-100 bg-white">
                <div class="grid grid-cols-2">
                    <div class="relative aspect-square">
                        <img src="{{ $entry['before'] }}" alt="Before — {{ $entry['caption'] }}" class="w-full h-full object-cover">
                        <span class="absolute top-2 left-2 bg-[#081d3a]/80 text-white text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded-full">Before</span>
                    </div>
                    <div class="relative aspect-square">
                        <img src="{{ $entry['after'] }}" alt="After — {{ $entry['caption'] }}" class="w-full h-full object-cover">
                        <span class="absolute top-2 right-2 bg-[#f6e304] text-[#081d3a] text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded-full">After</span>
                    </div>
                </div>
                <p class="text-[#647082] text-[13px] p-4">{{ $entry['caption'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
