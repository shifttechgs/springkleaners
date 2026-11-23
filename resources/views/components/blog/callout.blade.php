@props(['label' => 'Good to know'])

<aside class="guide-note">
    <p class="guide-note__label">{{ $label }}</p>
    <div class="guide-note__body">{{ $slot }}</div>
</aside>
