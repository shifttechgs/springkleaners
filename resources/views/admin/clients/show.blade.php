@extends('admin.layout')
@section('title', $client->name.' — Client')
@section('content')

    <div class="flex items-center gap-2 text-label text-[12px] font-medium mb-6">
        <a href="{{ route('admin.clients.index') }}" class="hover:text-navy transition-colors">Clients</a>
        <span>/</span>
        <span class="text-ink">{{ $client->name }}</span>
    </div>

    <div class="flex items-center gap-4 mb-8">
        <div class="avatar" style="width:44px;height:44px;border-radius:14px;font-size:16px;">{{ strtoupper(substr($client->name, 0, 1)) }}</div>
        <div>
            <h1 class="text-[22px] font-extrabold tracking-tight leading-tight">{{ $client->name }}</h1>
            <p class="text-muted text-[13px] mt-0.5">
                {{ $client->phone_raw }}
                @if ($client->email) &middot; {{ $client->email }} @endif
                @if ($client->suburb) &middot; {{ $client->suburb }} @endif
            </p>
        </div>
    </div>

    <div class="card overflow-hidden">
        <div class="px-6 py-4 border-b border-line">
            <h2 class="font-bold text-[14px] tracking-tight">Booking History</h2>
        </div>
        @if ($bookings->isEmpty())
            <div class="px-6 py-14 text-center">
                <p class="text-muted text-[13px]">No bookings yet.</p>
            </div>
        @else
            <table class="w-full data-table">
                <thead>
                    <tr>
                        <th class="px-6 py-3">Service</th>
                        <th class="px-6 py-3">Date</th>
                        <th class="px-6 py-3">Total</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @foreach ($bookings as $booking)
                        <tr>
                            <td class="px-6 py-3.5 font-medium">{{ \App\Support\Services::find($booking->service)['name'] ?? $booking->service }}</td>
                            <td class="px-6 py-3.5 text-muted">{{ $booking->date->format('d M Y') }}</td>
                            <td class="px-6 py-3.5 text-muted">R{{ number_format((float) ($booking->quoted_price ?? $booking->total ?? 0), 2) }}</td>
                            <td class="px-6 py-3.5"><x-admin.status-badge :status="$booking->status" /></td>
                            <td class="px-6 py-3.5 text-right">
                                <a href="{{ route('admin.bookings.show', $booking) }}" class="text-navy font-semibold hover:opacity-70 transition-opacity">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

@endsection
