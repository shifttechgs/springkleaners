@extends('admin.layout')
@section('title', 'Services')
@section('content')

    <div class="flex items-start justify-between mb-8 flex-wrap gap-4">
        <div>
            <p class="text-label text-[11px] uppercase tracking-wider font-semibold mb-1">Pricing</p>
            <h1 class="text-[26px] font-extrabold tracking-tight leading-none">Services</h1>
        </div>
        <a href="{{ route('admin.services.create') }}" class="btn-primary">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Add Service
        </a>
    </div>

    @if (session('status'))
        <div class="mb-6 px-4 py-3 bg-emerald-50 border border-emerald-100 rounded-xl text-emerald-700 text-[13px] font-medium">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 px-4 py-3 bg-rose-50 border border-rose-100 rounded-xl text-rose-600 text-[13px] font-medium">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="card overflow-hidden mb-8">
        @if ($services->isEmpty())
            <div class="px-6 py-14 text-center">
                <p class="text-muted text-[13px]">No services yet.</p>
            </div>
        @else
            <table class="w-full data-table">
                <thead>
                    <tr>
                        <th class="px-6 py-3">Service</th>
                        <th class="px-6 py-3">Base Price</th>
                        <th class="px-6 py-3">Included</th>
                        <th class="px-6 py-3">Extras (bed/bath/room)</th>
                        <th class="px-6 py-3">Service Fee</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @foreach ($services as $service)
                        <tr x-data="{ confirmOpen: false }">
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-2">
                                    <p class="font-semibold leading-tight">{{ $service->name }}</p>
                                    @unless ($service->bookable)
                                        <span class="text-[10px] font-semibold uppercase tracking-wide text-amber-600 bg-amber-50 border border-amber-100 rounded-full px-2 py-0.5">Quote only</span>
                                    @endunless
                                </div>
                                <p class="text-label text-[12px] leading-tight mt-0.5">{{ $service->slug }}</p>
                            </td>
                            <td class="px-6 py-3.5 text-ink font-semibold">R{{ number_format((float) $service->base_price, 2) }}</td>
                            <td class="px-6 py-3.5 text-muted">{{ $service->included_bedrooms }} bed / {{ $service->included_bathrooms }} bath</td>
                            <td class="px-6 py-3.5 text-muted">R{{ number_format((float) $service->bedroom_price, 0) }} / R{{ number_format((float) $service->bathroom_price, 0) }} / R{{ number_format((float) $service->extra_room_price, 0) }}</td>
                            <td class="px-6 py-3.5 text-muted">R{{ number_format((float) $service->service_fee, 2) }}</td>
                            <td class="px-6 py-3.5 text-right">
                                <div class="flex items-center justify-end gap-4">
                                    <a href="{{ route('admin.services.edit', $service) }}" class="text-navy font-semibold hover:opacity-70 transition-opacity">Edit</a>
                                    <button type="button" @click="confirmOpen = true" class="text-rose-500 font-semibold hover:opacity-70 transition-opacity">Delete</button>
                                </div>
                            </td>

                            <template x-teleport="body">
                                <div x-show="confirmOpen" x-cloak
                                     class="fixed inset-0 z-50 flex items-center justify-center p-4"
                                     style="background:rgba(8,29,58,0.45);"
                                     @click.self="confirmOpen = false">
                                    <div class="bg-white rounded-2xl p-6 w-full max-w-sm" style="box-shadow:0 20px 50px -12px rgba(0,0,0,0.4);">
                                        <h3 class="font-bold text-[16px] text-ink mb-2">Remove this service?</h3>
                                        <p class="text-muted text-[13px] mb-5">{{ $service->name }}. Existing bookings keep their recorded price — this only affects future quotes.</p>
                                        <div class="flex items-center gap-3">
                                            <button type="button" @click="confirmOpen = false" class="flex-1 px-4 py-2.5 rounded-xl text-[13px] font-semibold border border-line text-ink hover:border-navy transition-colors">
                                                Cancel
                                            </button>
                                            <form method="POST" action="{{ route('admin.services.destroy', $service) }}" class="flex-1">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full px-4 py-2.5 rounded-xl text-[13px] font-semibold bg-rose-500 text-white hover:bg-rose-600 transition-colors">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="flex items-start justify-between mb-4 flex-wrap gap-4">
        <h2 class="font-bold text-[16px] tracking-tight">Add-ons</h2>
        <a href="{{ route('admin.service-addons.create') }}" class="inline-flex items-center gap-2 bg-white border border-line text-ink font-semibold px-4 py-2.5 rounded-xl text-[13px] hover:border-navy transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Add Add-on
        </a>
    </div>

    <div class="card overflow-hidden">
        @if ($addons->isEmpty())
            <div class="px-6 py-14 text-center">
                <p class="text-muted text-[13px]">No add-ons yet.</p>
            </div>
        @else
            <table class="w-full data-table">
                <thead>
                    <tr>
                        <th class="px-6 py-3">Add-on</th>
                        <th class="px-6 py-3">Price</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @foreach ($addons as $addon)
                        <tr x-data="{ confirmOpen: false }">
                            <td class="px-6 py-3.5">
                                <p class="font-semibold leading-tight">{{ $addon->label }}</p>
                                @if ($addon->description)
                                    <p class="text-label text-[12px] leading-tight mt-0.5">{{ $addon->description }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-3.5 text-ink font-semibold">R{{ number_format((float) $addon->price, 2) }}</td>
                            <td class="px-6 py-3.5 text-right">
                                <div class="flex items-center justify-end gap-4">
                                    <a href="{{ route('admin.service-addons.edit', $addon) }}" class="text-navy font-semibold hover:opacity-70 transition-opacity">Edit</a>
                                    <button type="button" @click="confirmOpen = true" class="text-rose-500 font-semibold hover:opacity-70 transition-opacity">Delete</button>
                                </div>
                            </td>

                            <template x-teleport="body">
                                <div x-show="confirmOpen" x-cloak
                                     class="fixed inset-0 z-50 flex items-center justify-center p-4"
                                     style="background:rgba(8,29,58,0.45);"
                                     @click.self="confirmOpen = false">
                                    <div class="bg-white rounded-2xl p-6 w-full max-w-sm" style="box-shadow:0 20px 50px -12px rgba(0,0,0,0.4);">
                                        <h3 class="font-bold text-[16px] text-ink mb-2">Remove this add-on?</h3>
                                        <p class="text-muted text-[13px] mb-5">{{ $addon->label }}. This can't be undone.</p>
                                        <div class="flex items-center gap-3">
                                            <button type="button" @click="confirmOpen = false" class="flex-1 px-4 py-2.5 rounded-xl text-[13px] font-semibold border border-line text-ink hover:border-navy transition-colors">
                                                Cancel
                                            </button>
                                            <form method="POST" action="{{ route('admin.service-addons.destroy', $addon) }}" class="flex-1">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full px-4 py-2.5 rounded-xl text-[13px] font-semibold bg-rose-500 text-white hover:bg-rose-600 transition-colors">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

@endsection
