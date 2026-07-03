@props(['label' => 'Good to know'])

<div class="bg-white border border-black/10 rounded-2xl p-6 mb-8 relative overflow-hidden">
    <div class="absolute left-0 top-0 bottom-0 w-1 bg-[#f6e304]"></div>
    <p class="text-[11px] font-bold uppercase tracking-widest text-navy/60 mb-2">{{ $label }}</p>
    <div class="text-muted text-[15px] leading-relaxed">
        {{ $slot }}
    </div>
</div>
