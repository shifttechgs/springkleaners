@extends('admin.layout')
@section('title', 'Edit Service')
@section('content')

    <div class="flex items-center gap-2 text-label text-[12px] font-medium mb-6">
        <a href="{{ route('admin.services.index') }}" class="hover:text-navy transition-colors">Services</a>
        <span>/</span>
        <span class="text-ink">{{ $service->name }}</span>
    </div>

    @if ($errors->any())
        <div class="mb-6 px-4 py-3 bg-rose-50 border border-rose-100 rounded-xl text-rose-600 text-[13px] font-medium max-w-2xl">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="card p-6 max-w-2xl">
        <form method="POST" action="{{ route('admin.services.update', $service) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-label text-[11px] uppercase tracking-wider mb-1.5 font-semibold">Name</label>
                    <input type="text" name="name" value="{{ old('name', $service->name) }}" required
                           class="w-full bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
                </div>
                <div>
                    <label class="block text-label text-[11px] uppercase tracking-wider mb-1.5 font-semibold">Slug <span class="normal-case font-normal text-label">(URL, e.g. deep-cleaning)</span></label>
                    <input type="text" name="slug" value="{{ old('slug', $service->slug) }}" required
                           class="w-full bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
                </div>
            </div>

            <div>
                <label class="block text-label text-[11px] uppercase tracking-wider mb-1.5 font-semibold">Tagline <span class="normal-case font-normal text-label">(optional)</span></label>
                <input type="text" name="tagline" value="{{ old('tagline', $service->tagline) }}"
                       class="w-full bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-label text-[11px] uppercase tracking-wider mb-1.5 font-semibold">Base Price (R)</label>
                    <input type="number" step="0.01" min="0" name="base_price" value="{{ old('base_price', $service->base_price) }}" required
                           class="w-full bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
                </div>
                <div>
                    <label class="block text-label text-[11px] uppercase tracking-wider mb-1.5 font-semibold">Service Fee (R)</label>
                    <input type="number" step="0.01" min="0" name="service_fee" value="{{ old('service_fee', $service->service_fee) }}" required
                           class="w-full bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
                </div>
                <div>
                    <label class="block text-label text-[11px] uppercase tracking-wider mb-1.5 font-semibold">Avg Hours</label>
                    <input type="number" min="0" name="avg_hours" value="{{ old('avg_hours', $service->avg_hours) }}" required
                           class="w-full bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-label text-[11px] uppercase tracking-wider mb-1.5 font-semibold">Included Bedrooms</label>
                    <input type="number" min="0" name="included_bedrooms" value="{{ old('included_bedrooms', $service->included_bedrooms) }}" required
                           class="w-full bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
                </div>
                <div>
                    <label class="block text-label text-[11px] uppercase tracking-wider mb-1.5 font-semibold">Included Bathrooms</label>
                    <input type="number" min="0" name="included_bathrooms" value="{{ old('included_bathrooms', $service->included_bathrooms) }}" required
                           class="w-full bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-label text-[11px] uppercase tracking-wider mb-1.5 font-semibold">Extra Bedroom (R)</label>
                    <input type="number" step="0.01" min="0" name="bedroom_price" value="{{ old('bedroom_price', $service->bedroom_price) }}" required
                           class="w-full bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
                </div>
                <div>
                    <label class="block text-label text-[11px] uppercase tracking-wider mb-1.5 font-semibold">Extra Bathroom (R)</label>
                    <input type="number" step="0.01" min="0" name="bathroom_price" value="{{ old('bathroom_price', $service->bathroom_price) }}" required
                           class="w-full bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
                </div>
                <div>
                    <label class="block text-label text-[11px] uppercase tracking-wider mb-1.5 font-semibold">Extra Room (R)</label>
                    <input type="number" step="0.01" min="0" name="extra_room_price" value="{{ old('extra_room_price', $service->extra_room_price) }}" required
                           class="w-full bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-label text-[11px] uppercase tracking-wider mb-1.5 font-semibold">Unit Label <span class="normal-case font-normal text-label">(e.g. visit, property)</span></label>
                    <input type="text" name="unit_label" value="{{ old('unit_label', $service->unit_label) }}"
                           class="w-full bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
                </div>
                <div>
                    <label class="block text-label text-[11px] uppercase tracking-wider mb-1.5 font-semibold">Sort Order</label>
                    <input type="number" min="0" name="sort_order" value="{{ old('sort_order', $service->sort_order) }}"
                           class="w-full bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
                </div>
            </div>

            <label class="flex items-center gap-2.5 text-[13px] text-ink font-medium">
                <input type="checkbox" name="bookable" value="1" {{ old('bookable', $service->bookable) ? 'checked' : '' }}
                       class="w-4 h-4 rounded border-line text-navy focus:ring-navy">
                Bookable in the instant-quote wizard
                <span class="font-normal text-label">(leave unchecked for services priced by m²/room/seat/pane — those get a "Request a Quote" page instead)</span>
            </label>

            <button type="submit" class="btn-primary w-full justify-center">Save Changes</button>
        </form>
    </div>

@endsection
