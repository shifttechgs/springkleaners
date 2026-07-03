@props(['items'])

<ul class="space-y-3 mb-8">
    @foreach ($items as $item)
    <li class="flex items-start gap-3 text-muted text-[15px] leading-relaxed">
        <span class="w-5 h-5 rounded-full bg-[#f6e304]/20 flex items-center justify-center flex-shrink-0 mt-0.5">
            <svg class="w-3 h-3 text-[#081d3a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
        </span>
        <span>{{ $item }}</span>
    </li>
    @endforeach
</ul>
