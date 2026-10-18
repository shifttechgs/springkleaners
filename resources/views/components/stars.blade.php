@props(['class' => 'h-4 w-[88px]'])
{{-- Five gold stars as shapes (not text glyphs) so they stay visible and contrast-safe on light backgrounds. --}}
<svg {{ $attributes->merge(['class' => $class.' flex-shrink-0', 'viewBox' => '0 0 88 16', 'role' => 'img', 'aria-label' => 'Rated 5 out of 5']) }} fill="#f6c400" stroke="#9a6a00" stroke-width="0.7" stroke-linejoin="round">
    @foreach ([0, 18, 36, 54, 72] as $x)
        <path transform="translate({{ $x }} 0)" d="M8 .6l2.2 4.9 5.3.5-4 3.6 1.2 5.3L8 12.2 3.3 14.9l1.2-5.3-4-3.6 5.3-.5z"/>
    @endforeach
</svg>
