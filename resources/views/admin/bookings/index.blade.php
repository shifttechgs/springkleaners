@extends('admin.layout')
@section('title', 'Bookings & Quotes')
@section('content')

    <div class="flex items-start justify-between mb-8 flex-wrap gap-4">
        <div>
            <p class="text-label text-[11px] uppercase tracking-wider font-semibold mb-1">Pipeline</p>
            <h1 class="text-[26px] font-extrabold tracking-tight leading-none">Bookings &amp; Quotes</h1>
        </div>
        <a href="{{ route('admin.bookings.create') }}" class="btn-primary">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            New Booking
        </a>
    </div>

    <form method="GET" class="flex flex-wrap gap-2.5 mb-6">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or phone..."
               class="bg-white border border-line rounded-xl px-4 py-2.5 text-[13px] text-ink placeholder-label focus:border-navy focus:outline-none transition-colors w-56">
        <select name="status" class="bg-white border border-line rounded-xl px-4 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
            <option value="">All statuses</option>
            @foreach ($statuses as $status)
                <option value="{{ $status->value }}" @selected(request('status') === $status->value)>{{ $status->label() }}</option>
            @endforeach
        </select>
        <select name="service" class="bg-white border border-line rounded-xl px-4 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
            <option value="">All services</option>
            @foreach ($services as $slug => $service)
                <option value="{{ $slug }}" @selected(request('service') === $slug)>{{ $service['name'] }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn-primary">Filter</button>
        @if (request()->anyFilled(['search', 'status', 'service']))
            <a href="{{ route('admin.bookings.index') }}" class="flex items-center text-muted font-semibold text-[13px] hover:text-navy transition-colors px-1">Clear</a>
        @endif
    </form>

    <div class="card overflow-hidden">
        @if ($bookings->isEmpty())
            <div class="px-6 py-14 text-center">
                <p class="text-muted text-[13px]">No bookings match those filters.</p>
            </div>
        @else
            <table class="w-full data-table">
                <thead>
                    <tr>
                        <th class="px-6 py-3">Client</th>
                        <th class="px-6 py-3">Service</th>
                        <th class="px-6 py-3">Date</th>
                        <th class="px-6 py-3">Suburb</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @foreach ($bookings as $booking)
                        <tr>
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="avatar">{{ strtoupper(substr($booking->name, 0, 1)) }}</div>
                                    <div>
                                        <p class="font-semibold leading-tight">{{ $booking->name }}</p>
                                        <p class="text-label text-[12px] leading-tight mt-0.5">{{ $booking->phone }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-3.5 text-muted">{{ \App\Support\Services::find($booking->service)['name'] ?? $booking->service }}</td>
                            <td class="px-6 py-3.5 text-muted">{{ $booking->date->format('d M Y') }}</td>
                            <td class="px-6 py-3.5 text-muted">{{ $booking->suburb ?? '—' }}</td>
                            <td class="px-6 py-3.5"><x-admin.status-badge :status="$booking->status" /></td>
                            <td class="px-6 py-3.5 text-right">
                                <a href="{{ route('admin.bookings.show', $booking) }}" class="text-navy font-semibold hover:opacity-70 transition-opacity">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-6 py-4 border-t border-line">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>

@endsection
