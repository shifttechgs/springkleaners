{{-- Banking details for the client's online quote and invoice pages. --}}
<div class="{{ $spacing ?? 'mt-8' }} bg-white rounded-2xl border border-gray-100 p-6 sm:p-8 {{ $class ?? '' }}">
    <h2 class="text-[#081d3a] font-bold text-[16px] mb-1">Banking details</h2>
    @if (filled($intro ?? null))
        <p class="text-[#5b6779] text-[13.5px] mb-4">{{ $intro }}</p>
    @else
        <div class="mb-4"></div>
    @endif
    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3">
        @foreach (\App\Support\Company::bankingRows() as $row)
            <div>
                <dt class="text-[#8a94a6] text-[12px]">{{ $row['label'] }}</dt>
                <dd class="text-[#081d3a] text-[14.5px] font-semibold select-all">{{ $row['value'] }}</dd>
            </div>
        @endforeach
    </dl>
    @if (filled(\App\Support\Company::referenceNote()))
        <p class="mt-4 text-[#081d3a] text-[13.5px] bg-[#f8f9fc] rounded-lg px-3 py-2">{{ \App\Support\Company::referenceNote() }}</p>
    @endif
</div>
