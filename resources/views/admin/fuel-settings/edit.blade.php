@extends('admin.layout')
@section('title', 'Fuel Settings')
@section('content')

    @include('admin.settings._tabs')

    @if (session('status'))
        <div class="mb-5 px-4 py-3 bg-emerald-50 border border-emerald-100 rounded-xl text-emerald-700 text-[13px] font-medium">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-5 px-4 py-3 bg-rose-50 border border-rose-100 rounded-xl text-rose-600 text-[13px] font-medium">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="card overflow-hidden">
        <form method="POST" action="{{ route('admin.fuel-settings.update') }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 px-6 py-5 border-b border-line">
                <div class="lg:col-span-2">
                    <p class="font-semibold text-ink text-[13.5px]">Current petrol price (R per litre)</p>
                    <p class="text-muted text-[12.5px] mt-1 max-w-lg">SA fuel prices change roughly monthly — update this whenever it changes so job fuel-cost estimates stay accurate.</p>
                </div>
                <div class="lg:col-span-1">
                    <input type="number" step="0.01" min="0" name="petrol_price_per_litre" value="{{ old('petrol_price_per_litre', $petrolPrice) }}" required
                           class="w-full max-w-[180px] bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 px-6 py-5 border-b border-line">
                <div class="lg:col-span-2">
                    <p class="font-semibold text-ink text-[13.5px]">Vehicle efficiency (km per litre)</p>
                    <p class="text-muted text-[12.5px] mt-1 max-w-lg">How far your work vehicle typically travels on one litre of fuel — used to estimate fuel cost from a job's distance.</p>
                </div>
                <div class="lg:col-span-1">
                    <input type="number" step="0.1" min="0.1" name="vehicle_kmpl" value="{{ old('vehicle_kmpl', $vehicleKmpl) }}" required
                           class="w-full max-w-[180px] bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
                </div>
            </div>

            <div class="px-6 py-4 flex justify-end bg-canvas">
                <button type="submit" class="btn-primary">Save Changes</button>
            </div>
        </form>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 px-6 py-5 border-t border-line">
            <div class="lg:col-span-2">
                <p class="font-semibold text-ink text-[13.5px]">HQ address</p>
                <p class="text-muted text-[12.5px] mt-1 max-w-lg">Fuel distance for each job is estimated as a round trip from here. Fixed in code — contact your developer to change it.</p>
            </div>
            <div class="lg:col-span-1">
                <p class="text-ink text-[13px] py-2.5">{{ \App\Support\Company::hqAddress() }}</p>
            </div>
        </div>
    </div>

@endsection
