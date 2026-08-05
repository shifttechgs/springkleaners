@php
    $service = \App\Support\Services::find($booking->service);
    $scope = \App\Support\ServiceSnapshot::for($booking);
    $selectedAddons = collect($scope['addons']);
    $floorTypes = collect($booking->floor_types ?? []);
@endphp

<div class="space-y-5">
    <div class="flex items-center justify-between pb-4 border-b border-gray-100">
        <div>
            <p class="text-[#647082] text-[11px] uppercase tracking-wider font-semibold">Service</p>
            <p class="text-[#081d3a] font-bold text-[16px] mt-0.5">{{ $scope['name'] }}</p>
        </div>
        <div class="text-right">
            <p class="text-[#647082] text-[11px] uppercase tracking-wider font-semibold">
                {{ $booking->quoted_price ? 'Quoted Price' : 'Estimated Total' }}
            </p>
            <p class="text-[#081d3a] font-extrabold text-[22px] mt-0.5">
                R{{ number_format((float) ($booking->quoted_price ?? $booking->total ?? 0), 2) }}
            </p>
        </div>
    </div>

    @if (filled($scope['description']) || ! empty($scope['inclusions']))
        <div class="bg-[#f8f9fc] rounded-xl p-4">
            <p class="text-[#647082] text-[11px] uppercase tracking-wider font-semibold mb-2">What this service covers</p>
            @if (filled($scope['description']))
                <p class="text-[#4b5563] text-[13px] leading-relaxed mb-3">{{ $scope['description'] }}</p>
            @endif
            @if (! empty($scope['inclusions']))
                <ul class="grid sm:grid-cols-2 gap-x-4 gap-y-1.5">
                    @foreach ($scope['inclusions'] as $inclusion)
                        <li class="flex items-start gap-2 text-[13px] text-[#081d3a]">
                            <svg class="w-3.5 h-3.5 text-[#a9791f] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span>{{ $inclusion }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
            @if ($scope['avg_hours'])
                <p class="text-[#647082] text-[12px] mt-3">Typically around {{ $scope['avg_hours'] }} hours on site.</p>
            @endif
        </div>
    @endif

    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-[13px]">
        <div>
            <p class="text-[#647082] text-[11px] uppercase tracking-wider font-semibold mb-1">Property</p>
            <p class="text-[#081d3a] font-medium">{{ $booking->property_type ?? '—' }}</p>
        </div>
        <div>
            <p class="text-[#647082] text-[11px] uppercase tracking-wider font-semibold mb-1">Bedrooms</p>
            <p class="text-[#081d3a] font-medium">{{ $booking->bedrooms ?? '—' }}</p>
        </div>
        <div>
            <p class="text-[#647082] text-[11px] uppercase tracking-wider font-semibold mb-1">Bathrooms</p>
            <p class="text-[#081d3a] font-medium">{{ $booking->bathrooms ?? '—' }}</p>
        </div>
        <div>
            <p class="text-[#647082] text-[11px] uppercase tracking-wider font-semibold mb-1">Extra Rooms</p>
            <p class="text-[#081d3a] font-medium">{{ $booking->extra_rooms ?? '—' }}</p>
        </div>
        <div>
            <p class="text-[#647082] text-[11px] uppercase tracking-wider font-semibold mb-1">Last Cleaned</p>
            <p class="text-[#081d3a] font-medium">{{ $booking->last_cleaned ?: 'N/A' }}</p>
        </div>
        <div>
            <p class="text-[#647082] text-[11px] uppercase tracking-wider font-semibold mb-1">Pets</p>
            <p class="text-[#081d3a] font-medium">{{ $booking->pets ? 'Yes' : 'No' }}</p>
        </div>
        <div class="col-span-2 sm:col-span-3">
            <p class="text-[#647082] text-[11px] uppercase tracking-wider font-semibold mb-1">Flooring</p>
            <p class="text-[#081d3a] font-medium">{{ $floorTypes->isNotEmpty() ? $floorTypes->join(', ') : 'N/A' }}</p>
        </div>
    </div>

    <div>
        <p class="text-[#647082] text-[11px] uppercase tracking-wider font-semibold mb-2">Add-ons</p>
        @if ($selectedAddons->isNotEmpty())
            <ul class="space-y-2.5">
                @foreach ($selectedAddons as $addon)
                    <li class="flex items-start justify-between gap-4 text-[13px]">
                        <span>
                            <span class="text-[#081d3a] block">{{ $addon['label'] }}</span>
                            @if (filled($addon['description'] ?? null))
                                <span class="text-[#647082] text-[12px] block mt-0.5">{{ $addon['description'] }}</span>
                            @endif
                        </span>
                        <span class="text-[#647082] whitespace-nowrap">+R{{ number_format($addon['price']) }}</span>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-[#647082] text-[13px]">None selected</p>
        @endif
    </div>

    @if ($booking->notes || $booking->access_instructions || $booking->parking)
        <div class="pt-4 border-t border-gray-100 space-y-3 text-[13px]">
            @if ($booking->notes)
                <div>
                    <p class="text-[#647082] text-[11px] uppercase tracking-wider font-semibold mb-1">Special Instructions</p>
                    <p class="text-[#081d3a]">{{ $booking->notes }}</p>
                </div>
            @endif
            @if ($booking->access_instructions)
                <div>
                    <p class="text-[#647082] text-[11px] uppercase tracking-wider font-semibold mb-1">Access Instructions</p>
                    <p class="text-[#081d3a]">{{ $booking->access_instructions }}</p>
                </div>
            @endif
            @if ($booking->parking)
                <div>
                    <p class="text-[#647082] text-[11px] uppercase tracking-wider font-semibold mb-1">Parking</p>
                    <p class="text-[#081d3a]">{{ $booking->parking }}</p>
                </div>
            @endif
        </div>
    @endif

    <div class="pt-4 border-t border-gray-100 space-y-1.5 text-[13px]">
        <div class="flex justify-between text-[#647082]">
            <span>Subtotal</span>
            <span>R{{ number_format((float) ($booking->subtotal ?? 0), 2) }}</span>
        </div>
        <div class="flex justify-between text-[#647082]">
            <span>Service fee</span>
            <span>R{{ number_format((float) ($service['service_fee'] ?? 0), 2) }}</span>
        </div>
        <div class="flex justify-between text-[#081d3a] font-bold text-[15px] pt-1.5">
            <span>{{ $booking->quoted_price ? 'Quoted Price' : 'Estimated Total' }}</span>
            <span>R{{ number_format((float) ($booking->quoted_price ?? $booking->total ?? 0), 2) }}</span>
        </div>
    </div>
</div>
