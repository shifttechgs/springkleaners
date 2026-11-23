@props(['items'])
@php
    $faqJsonLd = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => collect($items)->map(fn ($item) => [
            '@type' => 'Question',
            'name' => $item['q'],
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => $item['a'],
            ],
        ])->all(),
    ];
@endphp
<script type="application/ld+json">{!! json_encode($faqJsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
{{-- Native <details>: opens without JavaScript, and every answer stays in the page for search engines. --}}
<div class="guide-faq">
    @foreach ($items as $item)
    <details class="guide-faq__item" @if ($loop->first) open @endif>
        <summary class="guide-faq__q">
            <h3>{{ $item['q'] }}</h3>
            <span class="guide-faq__icon" aria-hidden="true"></span>
        </summary>
        <p class="guide-faq__a">{{ $item['a'] }}</p>
    </details>
    @endforeach
</div>
