@props(['theme' => 'dark'])
@php
    $badges = [
        ['icon' => 'M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z', 'label' => 'Registered Company', 'sub' => 'Reg. 2021/363748/07'],
        ['icon' => 'M2.25 18L9 11.25l4.306 4.306a11.95 11.95 0 015.814-5.518l2.74-1.22m0 0l-5.94-2.281m5.94 2.28l-2.28 5.941', 'label' => 'Fully Insured', 'sub' => 'Public liability cover'],
        ['icon' => 'M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Background-Checked', 'sub' => 'Every team member vetted'],
        ['icon' => 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => '24-Hour Guarantee', 'sub' => 'Not happy? We return, free'],
    ];

    $isDark = $theme === 'dark';
@endphp
<div {{ $attributes->merge(['class' => 'flex flex-wrap gap-3']) }}>
    @foreach ($badges as $badge)
    <div class="flex items-center gap-2 px-3.5 py-2 rounded-xl border {{ $isDark ? 'border-white/15 bg-white/5' : 'border-gray-200 bg-[#f8f9fc]' }}">
        <svg class="w-4 h-4 flex-shrink-0 {{ $isDark ? 'text-[#f6e304]' : 'text-[#081d3a]' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $badge['icon'] }}"/></svg>
        <div class="leading-tight">
            <p class="{{ $isDark ? 'text-white' : 'text-[#081d3a]' }} text-[12px] font-semibold">{{ $badge['label'] }}</p>
            <p class="{{ $isDark ? 'text-white/40' : 'text-[#647082]' }} text-[10px]">{{ $badge['sub'] }}</p>
        </div>
    </div>
    @endforeach
</div>
