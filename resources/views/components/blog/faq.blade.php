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
<div class="mb-8">
    @foreach ($items as $item)
    <div class="mb-6">
        <h3 class="text-navy font-bold text-[17px] tracking-tight mb-2">{{ $item['q'] }}</h3>
        <p class="text-muted text-[15px] leading-[1.8]">{{ $item['a'] }}</p>
    </div>
    @endforeach
</div>
