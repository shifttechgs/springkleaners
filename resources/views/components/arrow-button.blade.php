@props(['href' => null, 'size' => 'md', 'block' => false, 'variant' => 'gold'])
{{-- Site-wide pill button. Variants: gold (default), navy (on gold surfaces), outline (on dark surfaces).
     Label plus an arrow chip. On hover the navy chip floods the button
     and the arrow swaps for a fresh one. Styles live in app.css (.btn-arrow). --}}
@php $tag = $href ? 'a' : 'button'; @endphp
<{{ $tag }}
    @if ($href) href="{{ $href }}" @else type="button" @endif
    {{ $attributes->class(['btn-arrow', 'btn-arrow--'.$variant, 'btn-arrow--sm' => $size === 'sm', 'btn-arrow--block' => $block]) }}
>
    <span class="btn-arrow__label">{{ $slot }}</span>
    <span class="btn-arrow__chip" aria-hidden="true">
        <svg class="btn-arrow__icon btn-arrow__icon--out" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17L17 7M8 7h9v9"/></svg>
        <svg class="btn-arrow__icon btn-arrow__icon--in" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17L17 7M8 7h9v9"/></svg>
    </span>
</{{ $tag }}>
