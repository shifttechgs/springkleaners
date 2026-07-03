@props(['heading' => 'Ready to book your clean?', 'text' => 'Get an accurate, no-obligation quote in minutes.'])

<div class="bg-[#081d3a] rounded-2xl px-6 sm:px-8 py-8 mb-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5">
    <div>
        <p class="text-white font-extrabold text-lg tracking-tight mb-1">{{ $heading }}</p>
        <p class="text-white/50 text-[14px]">{{ $text }}</p>
    </div>
    <a href="{{ route('booking.show') }}"
       class="inline-flex items-center gap-2 bg-[#f6e304] text-[#081d3a] font-bold px-6 py-3.5 rounded-xl hover:bg-yellow-300 active:scale-95 transition-all text-[14px] tracking-tight whitespace-nowrap flex-shrink-0">
        Get My Instant Quote
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
    </a>
</div>
