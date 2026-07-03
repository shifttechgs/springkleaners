@extends('admin.layout')
@section('title', 'Dashboard')
@section('contentPadding', 'px-8 py-5')
@section('content')

    {{-- Greeting header --}}
    <div class="flex items-center justify-between mb-3 flex-shrink-0 flex-wrap gap-2">
        <div class="flex items-baseline gap-2">
            <h1 class="text-[19px] font-extrabold tracking-tight leading-none">
                Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ explode(' ', auth()->user()->name)[0] }}
            </h1>
            <span class="text-muted text-[12px]">&middot; {{ now()->format('l, d F Y') }}</span>
        </div>
        <a href="{{ route('admin.bookings.index') }}" class="btn-primary !py-2 !px-3.5 !text-[12.5px]">
            View all bookings
            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4-4m4 4H3"/></svg>
        </a>
    </div>

    {{-- Revenue KPIs --}}
    @php
        $sparkW = 72; $sparkH = 22; $sparkPad = 3;
        $sparkValues = $revenueHistory->values();
        $sparkMax = $sparkValues->max();
        $sparkMin = $sparkValues->min();
        $sparkRange = max($sparkMax - $sparkMin, 1);
        $sparkCount = max($sparkValues->count() - 1, 1);
        $sparkPoints = $sparkValues->map(function ($v, $i) use ($sparkW, $sparkH, $sparkPad, $sparkMin, $sparkRange, $sparkCount) {
            $x = $sparkPad + ($i / $sparkCount) * ($sparkW - 2 * $sparkPad);
            $y = $sparkPad + (1 - ($v - $sparkMin) / $sparkRange) * ($sparkH - 2 * $sparkPad);
            return [round($x, 1), round($y, 1)];
        });
        $sparkLine = $sparkPoints->map(fn ($p) => "{$p[0]},{$p[1]}")->implode(' ');
        $sparkAreaPath = 'M'.$sparkPoints->first()[0].','.($sparkH - $sparkPad)
            .' L'.$sparkPoints->map(fn ($p) => "{$p[0]},{$p[1]}")->implode(' L')
            .' L'.$sparkPoints->last()[0].','.($sparkH - $sparkPad).' Z';
        $sparkEnd = $sparkPoints->last();
    @endphp

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-3 flex-shrink-0">

        <div class="card card-interactive animate-in delay-1 p-3">
            <p class="text-label text-[10px] uppercase tracking-wider font-semibold mb-1">Revenue This Month</p>
            <div class="flex items-end justify-between gap-2">
                <div>
                    <p class="font-extrabold text-[19px] tracking-tight leading-none">R{{ number_format($monthRevenue, 0) }}</p>
                    <p class="text-[11px] mt-1 flex items-center gap-1">
                        @if (is_null($revenueTrend))
                            <span class="text-label">No prior month</span>
                        @elseif ($revenueTrend >= 0)
                            <span class="text-emerald-600 font-semibold flex items-center gap-0.5">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5L12 3m0 0l7.5 7.5M12 3v18"/></svg>
                                {{ $revenueTrend }}%
                            </span>
                        @else
                            <span class="text-rose-500 font-semibold flex items-center gap-0.5">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 13.5L12 21m0 0l7.5-7.5M12 21V3"/></svg>
                                {{ abs($revenueTrend) }}%
                            </span>
                        @endif
                    </p>
                </div>
                <svg width="{{ $sparkW }}" height="{{ $sparkH }}" viewBox="0 0 {{ $sparkW }} {{ $sparkH }}" class="flex-shrink-0" aria-hidden="true">
                    <path d="{{ $sparkAreaPath }}" fill="#081d3a" opacity="0.08"/>
                    <polyline points="{{ $sparkLine }}" fill="none" stroke="#a8b3c2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <circle cx="{{ $sparkEnd[0] }}" cy="{{ $sparkEnd[1] }}" r="3.5" fill="#081d3a" stroke="#fff" stroke-width="1.5"/>
                </svg>
            </div>
        </div>

        <div class="card card-interactive animate-in delay-2 p-3">
            <p class="text-label text-[10px] uppercase tracking-wider font-semibold mb-1">Booked Revenue</p>
            <p class="font-extrabold text-[19px] tracking-tight leading-none">R{{ number_format($bookedRevenue, 0) }}</p>
            <p class="text-label text-[11px] mt-1">Confirmed, not completed</p>
        </div>

        <div class="card card-interactive animate-in delay-3 p-3">
            <p class="text-label text-[10px] uppercase tracking-wider font-semibold mb-1">Avg Job Value</p>
            <p class="font-extrabold text-[19px] tracking-tight leading-none">R{{ number_format($avgJobValue, 0) }}</p>
            <p class="text-label text-[11px] mt-1">Completed this month</p>
        </div>

        <div class="card card-interactive animate-in delay-4 p-3">
            <p class="text-label text-[10px] uppercase tracking-wider font-semibold mb-1">Win Rate</p>
            <p class="font-extrabold text-[19px] tracking-tight leading-none">{{ is_null($winRate) ? '—' : $winRate.'%' }}</p>
            <p class="text-label text-[11px] mt-1">Quotes &rarr; accepted</p>
        </div>

    </div>

    {{-- Needs Attention --}}
    @php
        $attentionItems = collect([
            $pendingCount > 0 ? [
                'text' => "{$pendingCount} new booking".($pendingCount > 1 ? 's' : '')." awaiting your review",
                'href' => route('admin.bookings.index', ['status' => 'pending']),
                'tone' => 'neutral',
            ] : null,
            $staleQuotes->isNotEmpty() ? [
                'text' => $staleQuotes->count()." quote".($staleQuotes->count() > 1 ? 's have' : ' has')." gone quiet — sent 3+ days ago with no response",
                'href' => route('admin.bookings.index', ['status' => 'quoted']),
                'tone' => 'warning',
            ] : null,
            $overdueJobs > 0 ? [
                'text' => "{$overdueJobs} accepted job".($overdueJobs > 1 ? 's' : '')." past their date — mark as completed",
                'href' => route('admin.bookings.index', ['status' => 'accepted']),
                'tone' => 'danger',
            ] : null,
            $jobsToday > 0 ? [
                'text' => "{$jobsToday} job".($jobsToday > 1 ? 's' : '')." scheduled today",
                'href' => route('admin.bookings.index', ['status' => 'accepted', 'date' => now()->toDateString()]),
                'tone' => 'info',
            ] : null,
            $jobsTomorrow > 0 ? [
                'text' => "{$jobsTomorrow} job".($jobsTomorrow > 1 ? 's' : '')." scheduled tomorrow",
                'href' => route('admin.bookings.index', ['status' => 'accepted', 'date' => now()->addDay()->toDateString()]),
                'tone' => 'info',
            ] : null,
        ])->filter()->values();

        $toneStyles = [
            'neutral' => ['bg' => 'rgba(136,152,170,0.08)', 'icon' => '#647082'],
            'warning' => ['bg' => 'rgba(247,144,9,0.1)', 'icon' => '#f79009'],
            'danger' => ['bg' => 'rgba(240,68,56,0.1)', 'icon' => '#f04438'],
            'info' => ['bg' => 'rgba(8,29,58,0.06)', 'icon' => '#081d3a'],
        ];
    @endphp

    @if ($attentionItems->isNotEmpty())
        <div class="card animate-in p-3 mb-3 flex-shrink-0">
            <h2 class="font-bold text-[12px] tracking-tight mb-1.5">Needs Attention</h2>
            <div class="space-y-1 max-h-24 overflow-y-auto">
                @foreach ($attentionItems as $item)
                    <a href="{{ $item['href'] }}"
                       class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-[12px] font-medium transition-all duration-150 hover:opacity-80 hover:translate-x-0.5"
                       style="background:{{ $toneStyles[$item['tone']]['bg'] }}; color:#0f2038;">
                        <span class="w-1.5 h-1.5 rounded-full flex-shrink-0" style="background:{{ $toneStyles[$item['tone']]['icon'] }};"></span>
                        <span>{{ $item['text'] }}</span>
                        <svg class="w-3 h-3 ml-auto flex-shrink-0 opacity-50" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Pipeline snapshot — single slim strip --}}
    <div class="card p-0 mb-3 flex-shrink-0 grid grid-cols-2 lg:grid-cols-4 divide-x divide-y-0 divide-line">
        <a href="{{ route('admin.bookings.index', ['status' => 'pending']) }}" class="px-4 py-2.5 hover:bg-canvas transition-colors flex items-center justify-between gap-2">
            <span class="text-label text-[11px] font-semibold uppercase tracking-wider">Pending</span>
            <span class="font-extrabold text-[15px] text-ink">{{ $pendingCount }}</span>
        </a>
        <a href="{{ route('admin.bookings.index', ['status' => 'quoted']) }}" class="px-4 py-2.5 hover:bg-canvas transition-colors flex items-center justify-between gap-2">
            <span class="text-label text-[11px] font-semibold uppercase tracking-wider">Quoted</span>
            <span class="font-extrabold text-[15px] text-ink">{{ $quotedCount }}</span>
        </a>
        <a href="{{ route('admin.bookings.index', ['status' => 'accepted']) }}" class="px-4 py-2.5 hover:bg-canvas transition-colors flex items-center justify-between gap-2">
            <span class="text-label text-[11px] font-semibold uppercase tracking-wider">Accepted</span>
            <span class="font-extrabold text-[15px] text-ink">{{ $acceptedCount }}</span>
        </a>
        <a href="{{ route('admin.clients.index') }}" class="px-4 py-2.5 hover:bg-canvas transition-colors flex items-center justify-between gap-2">
            <span class="text-label text-[11px] font-semibold uppercase tracking-wider">Clients</span>
            <span class="font-extrabold text-[15px] text-ink">{{ $clientCount }}</span>
        </a>
    </div>

    {{-- Expenses / profit snapshot — single slim strip --}}
    <div class="card p-0 mb-3 flex-shrink-0 grid grid-cols-3 divide-x divide-y-0 divide-line">
        <a href="{{ route('admin.expenses.index', ['from' => now()->startOfMonth()->toDateString(), 'to' => now()->endOfMonth()->toDateString()]) }}" class="px-4 py-2.5 hover:bg-canvas transition-colors flex items-center justify-between gap-2">
            <span class="text-label text-[11px] font-semibold uppercase tracking-wider">Expenses This Month</span>
            <span class="font-extrabold text-[15px] text-ink">R{{ number_format($monthExpenses, 0) }}</span>
        </a>
        <div class="px-4 py-2.5 flex items-center justify-between gap-2">
            <span class="text-label text-[11px] font-semibold uppercase tracking-wider">Net Profit</span>
            <span class="font-extrabold text-[15px] {{ $netProfit >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">R{{ number_format($netProfit, 0) }}</span>
        </div>
        <div class="px-4 py-2.5 flex items-center justify-between gap-2">
            <span class="text-label text-[11px] font-semibold uppercase tracking-wider">Profit Margin</span>
            <span class="font-extrabold text-[15px] text-ink">{{ is_null($profitMargin) ? '—' : $profitMargin.'%' }}</span>
        </div>
    </div>

    {{-- Bottom row: fills remaining height --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 flex-1 min-h-0">

        {{-- Upcoming Jobs Schedule --}}
        <div class="lg:col-span-2 card overflow-hidden flex flex-col min-h-0">
            <div class="px-5 py-3 border-b border-line flex items-center justify-between flex-shrink-0">
                <h2 class="font-bold text-[13px] tracking-tight">Upcoming Jobs</h2>
                <a href="{{ route('admin.bookings.index', ['status' => 'accepted']) }}" class="text-navy font-semibold text-[12px] hover:opacity-70 transition-opacity">View all &rarr;</a>
            </div>
            @if ($upcomingJobs->isEmpty())
                <div class="px-6 py-10 text-center">
                    <p class="text-muted text-[13px]">No confirmed jobs on the schedule yet.</p>
                </div>
            @else
                <div class="overflow-y-auto min-h-0">
                    <table class="w-full data-table">
                        <thead>
                            <tr>
                                <th class="px-5 py-2">Client</th>
                                <th class="px-5 py-2">Service</th>
                                <th class="px-5 py-2">Date</th>
                                <th class="px-5 py-2">Value</th>
                                <th class="px-5 py-2"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-line">
                            @foreach ($upcomingJobs as $booking)
                                <tr>
                                    <td class="px-5 py-2">
                                        <div class="flex items-center gap-2.5">
                                            <div class="avatar" style="width:26px;height:26px;border-radius:8px;font-size:11px;">{{ strtoupper(substr($booking->name, 0, 1)) }}</div>
                                            <span class="font-semibold text-[13px]">{{ $booking->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-2 text-muted text-[13px]">{{ \App\Support\Services::find($booking->service)['name'] ?? $booking->service }}</td>
                                    <td class="px-5 py-2 text-muted text-[13px]">{{ $booking->date->format('d M') }} &middot; {{ $booking->time }}</td>
                                    <td class="px-5 py-2 text-muted text-[13px]">R{{ number_format((float) ($booking->quoted_price ?? $booking->total ?? 0), 0) }}</td>
                                    <td class="px-5 py-2 text-right">
                                        <a href="{{ route('admin.bookings.show', $booking) }}" class="text-navy font-semibold text-[13px] hover:opacity-70 transition-opacity">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Calendar --}}
        <div class="card p-4 flex flex-col min-h-0">
            @php
                $prevMonth = $calendarMonth->copy()->subMonthNoOverflow();
                $nextMonth = $calendarMonth->copy()->addMonthNoOverflow();
                $firstWeekday = $calendarMonth->copy()->startOfMonth()->dayOfWeek; // 0 = Sunday
                $daysInMonth = $calendarMonth->daysInMonth;
                $todayStr = now()->toDateString();
            @endphp

            <div class="flex items-center justify-between mb-2 flex-shrink-0">
                <h2 class="font-bold text-[13px] tracking-tight">{{ $calendarMonth->format('F Y') }}</h2>
                <div class="flex items-center gap-1">
                    <a href="{{ route('admin.dashboard', ['month' => $prevMonth->format('Y-m')]) }}" class="w-6 h-6 flex items-center justify-center rounded-lg border border-line hover:border-navy transition-colors">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                    </a>
                    <a href="{{ route('admin.dashboard', ['month' => $nextMonth->format('Y-m')]) }}" class="w-6 h-6 flex items-center justify-center rounded-lg border border-line hover:border-navy transition-colors">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-7 gap-0.5 mb-1 flex-shrink-0">
                @foreach (['S', 'M', 'T', 'W', 'T', 'F', 'S'] as $i => $label)
                    <div class="text-center text-label text-[9.5px] font-semibold uppercase" style="{{ $i === 0 || $i === 6 ? 'color:#081d3a;' : '' }}">{{ $label }}</div>
                @endforeach
            </div>

            <div class="grid grid-cols-7 gap-0.5 flex-1 min-h-0" style="grid-auto-rows: 1fr;">
                @for ($i = 0; $i < $firstWeekday; $i++)
                    <div></div>
                @endfor

                @for ($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        $cellDate = $calendarMonth->copy()->startOfMonth()->addDays($day - 1);
                        $dateStr = $cellDate->toDateString();
                        $isWeekend = in_array($cellDate->dayOfWeek, [0, 6], true);
                        $dayData = $calendarDays[$dateStr] ?? null;
                        $isToday = $dateStr === $todayStr;
                    @endphp

                    @if ($dayData)
                        <a href="{{ route('admin.bookings.index', ['date' => $dateStr]) }}"
                           class="rounded-md flex flex-col items-center justify-center relative hover:ring-2 transition-all"
                           style="{{ $isToday ? 'background:#081d3a; color:#fff;' : ($isWeekend ? 'background:rgba(246,227,4,0.12);' : 'background:#f6f7fa;') }} {{ $isToday ? '' : 'ring-color:#081d3a;' }}">
                            <span class="text-[11px] font-semibold {{ $isToday ? 'text-white' : 'text-ink' }}">{{ $day }}</span>
                            <span class="flex gap-0.5">
                                @if ($dayData['accepted'] > 0)
                                    <span class="w-1 h-1 rounded-full" style="background:{{ $isToday ? '#f6e304' : '#12b76a' }};"></span>
                                @endif
                                @if ($dayData['other'] > 0)
                                    <span class="w-1 h-1 rounded-full" style="background:{{ $isToday ? '#fff' : '#f79009' }};"></span>
                                @endif
                            </span>
                        </a>
                    @else
                        <div class="rounded-md flex items-center justify-center"
                             style="{{ $isToday ? 'background:#081d3a;' : ($isWeekend ? 'background:rgba(246,227,4,0.06);' : '') }}">
                            <span class="text-[11px] {{ $isToday ? 'text-white font-semibold' : ($isWeekend ? 'text-ink' : 'text-label') }}">{{ $day }}</span>
                        </div>
                    @endif
                @endfor
            </div>

            <div class="flex items-center gap-3 mt-2 pt-2 border-t border-line flex-shrink-0">
                <div class="flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span class="text-label text-[10.5px]">Confirmed</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                    <span class="text-label text-[10.5px]">Pending/quoted</span>
                </div>
            </div>
        </div>

    </div>

@endsection
