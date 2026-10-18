@props(['heading' => 'Ready to book your clean?', 'text' => 'Get an accurate, no-obligation quote in minutes.'])

<div class="bg-[#081d3a] rounded-2xl px-6 sm:px-8 py-8 mb-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5">
    <div>
        <p class="text-white font-extrabold text-lg tracking-tight mb-1">{{ $heading }}</p>
        <p class="text-white/50 text-[14px]">{{ $text }}</p>
    </div>
    <x-arrow-button href="{{ route('booking.show') }}" class="flex-shrink-0">Get My Instant Quote</x-arrow-button>
</div>
