@props(['heading' => 'Rather hand this over?', 'text' => 'See your price online in a minute. No payment upfront.', 'service' => null])
{{-- One quiet offer of help inside a guide: the guide itself is the point, so this stays low-key. --}}
<aside class="guide-help print:hidden">
    <div class="guide-help__copy">
        <p class="guide-help__title">{{ $heading }}</p>
        <p class="guide-help__text">{{ $text }}</p>
    </div>
    <div class="guide-help__actions">
        <x-arrow-button href="{{ route('booking.show', array_filter(['service' => $service])) }}" class="flex-shrink-0">See your price</x-arrow-button>
        <a href="{{ \App\Support\WhatsApp::question() }}" target="_blank" rel="noopener noreferrer" class="guide-help__link">Ask us on WhatsApp</a>
    </div>
</aside>
