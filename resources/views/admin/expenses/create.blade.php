@extends('admin.layout')
@section('title', 'Add Expense')
@section('content')

    <div class="flex items-center gap-2 text-label text-[12px] font-medium mb-6">
        <a href="{{ route('admin.expenses.index') }}" class="hover:text-navy transition-colors">Expenses</a>
        <span>/</span>
        <span class="text-ink">Add Expense</span>
    </div>

    @if ($errors->any())
        <div class="mb-6 px-4 py-3 bg-rose-50 border border-rose-100 rounded-xl text-rose-600 text-[13px] font-medium max-w-2xl">
            {{ $errors->first() }}
        </div>
    @endif

    <div x-data="{
            category: {{ Illuminate\Support\Js::from(old('category', $booking ? 'fuel' : 'stock')) }},
            petrolPrice: {{ $petrolPrice }},
            vehicleKmpl: {{ $vehicleKmpl }},
            distanceKm: {{ Illuminate\Support\Js::from(old('distance_km', $suggestedDistanceKm ?? '')) }},
            get litres() { return this.distanceKm ? (this.distanceKm / this.vehicleKmpl) : 0 },
            get fuelCost() { return this.litres * this.petrolPrice },
            applyFuelCalc() {
                if (!this.distanceKm) return;
                document.getElementById('amount').value = this.fuelCost.toFixed(2);
                document.getElementById('description').value = 'Fuel — ' + this.distanceKm + 'km @ R' + this.petrolPrice.toFixed(2) + '/L, ' + this.vehicleKmpl + 'km/L';
            }
         }" class="card p-6 max-w-2xl">

        <form method="POST" action="{{ route('admin.expenses.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-label text-[11px] uppercase tracking-wider mb-1.5 font-semibold">Link to Job <span class="normal-case font-normal text-label">(optional)</span></label>
                <select name="booking_id"
                        @change="window.location = '{{ route('admin.expenses.create') }}?category=' + category + ($event.target.value ? '&booking_id=' + $event.target.value : '')"
                        class="w-full bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
                    <option value="">&mdash; No job &mdash;</option>
                    @foreach ($bookings as $b)
                        <option value="{{ $b->id }}" @selected($booking && $booking->id === $b->id)>{{ $b->name }} &mdash; {{ $b->date->format('d M Y') }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-label text-[11px] uppercase tracking-wider mb-1.5 font-semibold">Category</label>
                    <select name="category" x-model="category" class="w-full bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
                        @foreach ($categories as $category)
                            <option value="{{ $category->value }}" @selected(old('category', $booking ? 'fuel' : 'stock') === $category->value)>{{ $category->label() }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-label text-[11px] uppercase tracking-wider mb-1.5 font-semibold">Date</label>
                    <input type="date" name="date" value="{{ old('date', now()->toDateString()) }}" required
                           class="w-full bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
                </div>
            </div>

            <div x-show="category === 'fuel'" x-cloak class="bg-canvas rounded-xl p-4">
                <p class="text-label text-[11px] uppercase tracking-wider font-semibold mb-3">Fuel Calculator</p>
                <div class="grid grid-cols-3 gap-3 mb-3">
                    <div>
                        <label class="block text-label text-[10.5px] uppercase tracking-wider mb-1 font-semibold">Distance (km)</label>
                        <input type="number" step="0.1" min="0" x-model.number="distanceKm"
                               class="w-full bg-white border border-line rounded-xl px-3 py-2 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
                        @if ($booking && $suggestedDistanceKm)
                            <p class="text-label text-[10.5px] mt-1">Estimated: HQ &rarr; job address, round trip. Adjust if needed.</p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-label text-[10.5px] uppercase tracking-wider mb-1 font-semibold">Petrol Price (R/L)</label>
                        <p class="px-3 py-2 text-[13px] text-ink" x-text="'R' + petrolPrice.toFixed(2)"></p>
                    </div>
                    <div>
                        <label class="block text-label text-[10.5px] uppercase tracking-wider mb-1 font-semibold">Efficiency (km/L)</label>
                        <p class="px-3 py-2 text-[13px] text-ink" x-text="vehicleKmpl + ' km/L'"></p>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <p class="text-[13px] text-ink">Estimated cost: <span class="font-bold" x-text="'R' + fuelCost.toFixed(2)"></span></p>
                    <button type="button" @click="applyFuelCalc()" class="btn-gold !py-2 !px-3.5 !text-[12px]">Use this amount</button>
                </div>
                <p class="text-label text-[11px] mt-2">
                    <a href="{{ route('admin.fuel-settings.edit') }}" class="hover:text-navy transition-colors">Update petrol price / efficiency &rarr;</a>
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-label text-[11px] uppercase tracking-wider mb-1.5 font-semibold">Amount (R)</label>
                    <input type="number" id="amount" step="0.01" min="0" name="amount" value="{{ old('amount') }}" required
                           class="w-full bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
                </div>
                <div>
                    <label class="block text-label text-[11px] uppercase tracking-wider mb-1.5 font-semibold">Payee <span class="normal-case font-normal text-label">(optional)</span></label>
                    <input type="text" name="payee" value="{{ old('payee') }}" placeholder="e.g. Builders Warehouse, Jane Doe"
                           class="w-full bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
                </div>
            </div>

            <div>
                <label class="block text-label text-[11px] uppercase tracking-wider mb-1.5 font-semibold">Description</label>
                <input type="text" id="description" name="description" value="{{ old('description') }}" required
                       class="w-full bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
            </div>

            <div x-data="{ recurring: {{ old('is_recurring') ? 'true' : 'false' }} }">
                <div class="flex items-center justify-between bg-canvas rounded-xl px-4 py-3">
                    <span class="text-ink text-[13px] font-semibold">This is a recurring expense</span>
                    <button type="button" @click="recurring = !recurring" class="w-11 h-6 rounded-full relative transition-colors flex-shrink-0" :class="recurring ? 'bg-navy' : 'bg-[#dbe0ea]'">
                        <input type="hidden" name="is_recurring" :value="recurring ? 1 : 0">
                        <span class="absolute top-0.5 w-5 h-5 bg-white rounded-full shadow transition-all" :class="recurring ? 'left-[22px]' : 'left-0.5'"></span>
                    </button>
                </div>
                <div x-show="recurring" x-cloak class="mt-3">
                    <input type="text" name="recurrence_note" value="{{ old('recurrence_note') }}" placeholder="e.g. Monthly, due on the 1st"
                           class="w-full bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
                </div>
            </div>

            <div>
                <label class="block text-label text-[11px] uppercase tracking-wider mb-1.5 font-semibold">Notes <span class="normal-case font-normal text-label">(optional)</span></label>
                <textarea name="notes" rows="2" class="w-full bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors resize-none">{{ old('notes') }}</textarea>
            </div>

            <button type="submit" class="btn-primary w-full justify-center">Save Expense</button>
        </form>
    </div>

@endsection
