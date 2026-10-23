@extends('admin.layout')
@section('title', 'Clients')
@section('content')

    <div class="mb-8">
        <p class="text-label text-[11px] uppercase tracking-wider font-semibold mb-1">CRM</p>
        <h1 class="text-[26px] font-extrabold tracking-tight leading-none">Clients</h1>
    </div>

    <form method="GET" class="filter-bar flex flex-wrap gap-2.5 mb-6">
        <input type="text" name="search" enterkeyhint="search" value="{{ request('search') }}" placeholder="Search name or phone..."
               class="bg-white border border-line rounded-xl px-4 py-2.5 text-[13px] text-ink placeholder-label focus:border-navy focus:outline-none transition-colors w-full sm:w-64">
        <button type="submit" class="btn-primary">Search</button>
        @if (request()->filled('search'))
            <a href="{{ route('admin.clients.index') }}" class="flex items-center text-muted font-semibold text-[13px] hover:text-navy transition-colors px-1">Clear</a>
        @endif
    </form>

    <div class="card overflow-hidden">
        @if ($clients->isEmpty())
            <div class="px-6 py-14 text-center">
                <p class="text-muted text-[13px]">No clients yet — they're created automatically from booking submissions.</p>
            </div>
        @else
            <div class="table-scroll">
            <table class="w-full data-table">
                <thead>
                    <tr>
                        <th class="px-6 py-3">Client</th>
                        <th class="px-6 py-3">Phone</th>
                        <th class="px-6 py-3">Suburb</th>
                        <th class="px-6 py-3">Bookings</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @foreach ($clients as $client)
                        <tr>
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="avatar">{{ strtoupper(substr($client->name, 0, 1)) }}</div>
                                    <span class="font-semibold">{{ $client->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-3.5 text-muted" data-label="Phone">{{ $client->phone_raw }}</td>
                            <td class="px-6 py-3.5 text-muted" data-label="Suburb">{{ $client->suburb ?? '—' }}</td>
                            <td class="px-6 py-3.5 text-muted" data-label="Bookings">{{ $client->bookings_count }}</td>
                            <td class="px-6 py-3.5 text-right td-corner">
                                <a href="{{ route('admin.clients.show', $client) }}" class="row-link text-navy font-semibold hover:opacity-70 transition-opacity">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            @if ($clients->hasPages())
            <div class="px-6 py-4 border-t border-line">
                {{ $clients->links() }}
            </div>
            @endif
        @endif
    </div>

@endsection
