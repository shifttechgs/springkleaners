@extends('admin.layout')
@section('title', 'Add Add-on')
@section('content')

    <div class="flex items-center gap-2 text-label text-[12px] font-medium mb-6">
        <a href="{{ route('admin.services.index') }}" class="hover:text-navy transition-colors">Services</a>
        <span>/</span>
        <span class="text-ink">Add Add-on</span>
    </div>

    @if ($errors->any())
        <div class="mb-6 px-4 py-3 bg-rose-50 border border-rose-100 rounded-xl text-rose-600 text-[13px] font-medium max-w-2xl">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="card p-6 max-w-2xl">
        <form method="POST" action="{{ route('admin.service-addons.store') }}" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-label text-[11px] uppercase tracking-wider mb-1.5 font-semibold">Label</label>
                    <input type="text" name="label" value="{{ old('label') }}" required
                           class="w-full bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
                </div>
                <div>
                    <label class="block text-label text-[11px] uppercase tracking-wider mb-1.5 font-semibold">Key <span class="normal-case font-normal text-label">(internal, e.g. windows)</span></label>
                    <input type="text" name="key" value="{{ old('key') }}" required
                           class="w-full bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-label text-[11px] uppercase tracking-wider mb-1.5 font-semibold">Price (R)</label>
                    <input type="number" step="0.01" min="0" name="price" value="{{ old('price') }}" required
                           class="w-full bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
                </div>
                <div>
                    <label class="block text-label text-[11px] uppercase tracking-wider mb-1.5 font-semibold">Sort Order</label>
                    <input type="number" min="0" name="sort_order" value="{{ old('sort_order', 0) }}"
                           class="w-full bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
                </div>
            </div>

            <div>
                <label class="block text-label text-[11px] uppercase tracking-wider mb-1.5 font-semibold">Description <span class="normal-case font-normal text-label">(optional)</span></label>
                <input type="text" name="description" value="{{ old('description') }}"
                       class="w-full bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
            </div>

            <button type="submit" class="btn-primary w-full justify-center">Save Add-on</button>
        </form>
    </div>

@endsection
