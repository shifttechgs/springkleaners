@extends('admin.layout')
@section('title', 'Dashboard')
@section('contentPadding', 'px-4 py-5 sm:px-6 lg:px-8')
@section('contentHeight', 'h-full')
@section('navAction')
    <button type="button" class="ios-nav-btn" aria-label="Refresh" x-data="{ spin: false }" x-on:click="spin = true; location.reload()">
        <svg class="h-[22px] w-[22px]" :class="spin && 'animate-spin'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
    </button>
@endsection
@section('content')

    {{-- Phones get a trimmed, re-ordered dashboard (CSS order): greeting, two revenue cards, pipeline counts,
         needs attention, calendar, upcoming jobs. Avg job value, win rate, the profit strip and the client count stay desktop-only. --}}

    {{-- Greeting header --}}
    <div class="flex items-center justify-between mb-3 flex-shrink-0 flex-wrap gap-2">
        <div class="flex items-baseline gap-x-2 gap-y-1 flex-wrap">
            <h1 class="dash-greeting text-[19px] font-extrabold tracking-tight leading-none">
                Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ explode(' ', auth()->user()->name)[0] }}
            </h1>
            <span class="text-muted text-[12px]"><span class="max-lg:hidden">&middot; </span>{{ now()->format('l, d F Y') }}<span class="lg:hidden"> &middot; Updated {{ now()->format('H:i') }}</span></span>
        </div>
        <a href="{{ route('admin.bookings.index') }}" class="btn-primary !py-2 !px-3.5 !text-[12.5px] max-lg:hidden!">
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

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-3 flex-shrink-0 max-lg:order-1">

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

        <div class="card card-interactive animate-in delay-3 p-3 max-lg:hidden">
            <p class="text-label text-[10px] uppercase tracking-wider font-semibold mb-1">Avg Job Value</p>
            <p class="font-extrabold text-[19px] tracking-tight leading-none">R{{ number_format($avgJobValue, 0) }}</p>
            <p class="text-label text-[11px] mt-1">Completed this month</p>
        </div>

        <div class="card card-interactive animate-in delay-4 p-3 max-lg:hidden">
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
        <div class="card animate-in p-3 mb-3 flex-shrink-0 max-lg:order-3">
            <h2 class="font-bold text-[12px] max-lg:text-[15px] tracking-tight mb-1.5">Needs Attention</h2>
            <div class="space-y-1 lg:max-h-24 lg:overflow-y-auto">
                @foreach ($attentionItems as $item)
                    <a href="{{ $item['href'] }}"
                       class="flex items-center gap-2.5 px-3 py-1.5 max-lg:py-3 rounded-lg text-[12px] max-lg:text-[15px] font-medium transition-all duration-150 hover:opacity-80 hover:translate-x-0.5"
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
    <div class="card p-0 mb-3 flex-shrink-0 grid grid-cols-3 lg:grid-cols-4 divide-x divide-y-0 divide-line max-lg:order-2">
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
        <a href="{{ route('admin.clients.index') }}" class="max-lg:hidden px-4 py-2.5 hover:bg-canvas transition-colors flex items-center justify-between gap-2">
            <span class="text-label text-[11px] font-semibold uppercase tracking-wider">Clients</span>
            <span class="font-extrabold text-[15px] text-ink">{{ $clientCount }}</span>
        </a>
    </div>

    {{-- Expenses / profit snapshot — single slim strip --}}
    <div class="card p-0 mb-3 flex-shrink-0 grid grid-cols-3 divide-x divide-y-0 divide-line max-lg:hidden">
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
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:flex-1 lg:min-h-0 max-lg:order-4 max-lg:mb-3">

        {{-- Upcoming Jobs Schedule --}}
        <div class="lg:col-span-2 card overflow-hidden flex flex-col lg:min-h-0">
            <div class="px-5 py-3 border-b border-line flex items-center justify-between flex-shrink-0">
                <h2 class="font-bold text-[13px] tracking-tight">
                    Upcoming Jobs
                    @if ($upcomingCount > 0)
                        <span class="ml-1 text-label font-semibold text-[12px]">{{ $upcomingCount > $upcomingJobs->count() ? $upcomingJobs->count().' of '.$upcomingCount : $upcomingCount }}</span>
                    @endif
                </h2>
                <a href="{{ route('admin.bookings.index', ['when' => 'upcoming']) }}" class="text-navy font-semibold text-[12px] hover:opacity-70 transition-opacity">View all &rarr;</a>
            </div>
            @if ($upcomingJobs->isEmpty())
                <div class="px-6 py-10 text-center">
                    <p class="text-muted text-[13px]">No upcoming jobs on the schedule.</p>
                    <a href="{{ route('admin.bookings.create') }}" class="inline-block mt-2 text-navy font-semibold text-[12.5px] hover:opacity-70 transition-opacity">+ Add a booking</a>
                </div>
            @else
                <div class="overflow-y-auto min-h-0">
                    <table class="w-full data-table">
                        <thead>
                            <tr>
                                <th class="px-5 py-2">Client</th>
                                <th class="px-5 py-2">Service</th>
                                <th class="px-5 py-2">Date</th>
                                <th class="px-5 py-2">Status</th>
                                <th class="px-5 py-2">Value</th>
                                <th class="px-5 py-2"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-line">
                            @foreach ($upcomingJobs as $booking)
                                <tr data-swipe>
                                    <td class="px-5 py-2">
                                        <div class="flex items-center gap-2.5">
                                            <div class="avatar" style="width:26px;height:26px;border-radius:8px;font-size:11px;">{{ strtoupper(substr($booking->name, 0, 1)) }}</div>
                                            <span class="font-semibold text-[13px]">{{ $booking->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-2 text-muted text-[13px]" data-label="Service">{{ \App\Support\Services::find($booking->service)['name'] ?? $booking->service }}</td>
                                    <td class="px-5 py-2 text-muted text-[13px]" data-label="Date">
                                        <span class="font-semibold text-ink">{{ $booking->date->isToday() ? 'Today' : ($booking->date->isTomorrow() ? 'Tomorrow' : $booking->date->format('D d M')) }}</span>
                                        &middot; {{ $booking->time }}
                                    </td>
                                    <td class="px-5 py-2" data-label="Status"><x-admin.status-badge :status="$booking->status" /></td>
                                    <td class="px-5 py-2 text-muted text-[13px] td-corner">R{{ number_format((float) ($booking->quoted_price ?? $booking->total ?? 0), 0) }}</td>
                                    <td class="px-5 py-2 text-right td-link">
                                        <a href="{{ route('admin.bookings.show', $booking) }}" class="row-link text-navy font-semibold text-[13px] hover:opacity-70 transition-opacity">View</a>
                                    </td>
                                    @include('admin._swipe-actions', ['phone' => $booking->phone, 'name' => $booking->name])
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Calendar. Click any day to see its jobs (and which slots are free) without leaving the dashboard. --}}
        @php $closedDates = \App\Models\Booking::closedDates(); @endphp
        <div class="card p-4 flex flex-col lg:min-h-0 max-lg:order-first"
             x-data="{
                 days: {{ \Illuminate\Support\Js::from($calendarDays) }},
                 today: {{ \Illuminate\Support\Js::from(now()->toDateString()) }},
                 bookableDays: {{ \Illuminate\Support\Js::from(\App\Models\Booking::BOOKABLE_DAYS) }},
                 closedDates: {{ \Illuminate\Support\Js::from((object) $closedDates) }},
                 slotLabels: {{ \Illuminate\Support\Js::from(['am' => \App\Models\Booking::MORNING_SLOT, 'pm' => \App\Models\Booking::AFTERNOON_SLOT]) }},
                 listUrl: {{ \Illuminate\Support\Js::from(route('admin.bookings.index')) }},
                 createUrl: {{ \Illuminate\Support\Js::from(route('admin.bookings.create')) }},
                 open: null,
                 openDay(date) { this.open = date; this.$nextTick(() => this.$refs.dayClose?.focus()); },
                 close() { this.open = null; },
                 get day() { return this.open ? (this.days[this.open] || { jobs: [] }) : null },
                 get label() { return this.open ? new Date(this.open + 'T00:00:00').toLocaleDateString('en-GB', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' }) : '' },
                 get isPast() { return this.open && this.open < this.today },
                 get closedReason() { return this.open && Object.prototype.hasOwnProperty.call(this.closedDates, this.open) ? this.closedDates[this.open] : null },
                 get isBookable() { return this.open && !this.isPast && !this.closedReason && this.bookableDays.includes(new Date(this.open + 'T00:00:00').getDay()) },
                 jobsIn(slot) { return this.day ? this.day.jobs.filter(j => j.slot === slot) : [] },
             }"
             x-on:keydown.escape.window="close()">
            @php
                $prevMonth = $calendarMonth->copy()->subMonthNoOverflow();
                $nextMonth = $calendarMonth->copy()->addMonthNoOverflow();
                $firstWeekday = $calendarMonth->copy()->startOfMonth()->dayOfWeek; // 0 = Sunday
                $daysInMonth = $calendarMonth->daysInMonth;
                $todayStr = now()->toDateString();
            @endphp

            <div class="flex items-center justify-between mb-2 flex-shrink-0">
                <h2 class="font-bold text-[13px] max-lg:text-[15px] tracking-tight">{{ $calendarMonth->format('F Y') }}</h2>
                <div class="flex items-center gap-1">
                    <a href="{{ route('admin.dashboard', ['month' => $prevMonth->format('Y-m')]) }}" class="w-9 h-9 lg:w-6 lg:h-6 flex items-center justify-center rounded-lg border border-line hover:border-navy transition-colors">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                    </a>
                    <a href="{{ route('admin.dashboard', ['month' => $nextMonth->format('Y-m')]) }}" class="w-9 h-9 lg:w-6 lg:h-6 flex items-center justify-center rounded-lg border border-line hover:border-navy transition-colors">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                    </a>
                </div>
            </div>

            {{-- The next job on the schedule, even when it's in another month. --}}
            <div class="mb-2 flex-shrink-0 text-[11.5px] max-lg:text-[13px] truncate">
                @if ($nextJob)
                    @php
                        $nextInView = $nextJob->date->format('Y-m') === $calendarMonth->format('Y-m');
                    @endphp
                    <span class="text-label">Next job:</span>
                    <a href="{{ $nextInView ? route('admin.bookings.show', $nextJob) : route('admin.dashboard', ['month' => $nextJob->date->format('Y-m')]) }}"
                       class="font-semibold text-navy hover:opacity-70 transition-opacity">
                        {{ $nextJob->date->isToday() ? 'Today' : ($nextJob->date->isTomorrow() ? 'Tomorrow' : $nextJob->date->format('D j M')) }}
                        &middot; {{ $nextJob->name }}@unless ($nextInView) &rarr;@endunless
                    </a>
                @else
                    <span class="text-label">No upcoming jobs.</span>
                @endif
            </div>

            <div class="grid grid-cols-7 gap-0.5 mb-1 flex-shrink-0">
                @foreach (['S', 'M', 'T', 'W', 'T', 'F', 'S'] as $i => $label)
                    <div class="text-center text-label text-[9.5px] max-lg:text-[11px] font-semibold uppercase" style="{{ $i === 0 || $i === 6 ? 'color:#081d3a;' : '' }}">{{ $label }}</div>
                @endforeach
            </div>

            <div class="grid grid-cols-7 gap-0.5 lg:flex-1 lg:min-h-0 auto-rows-[2.75rem] lg:auto-rows-fr">
                @for ($i = 0; $i < $firstWeekday; $i++)
                    <div></div>
                @endfor

                @for ($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        $cellDate = $calendarMonth->copy()->startOfMonth()->addDays($day - 1);
                        $dateStr = $cellDate->toDateString();
                        $isWeekend = in_array($cellDate->dayOfWeek, [0, 6], true);
                        $isClosed = array_key_exists($dateStr, $closedDates); // config/closed_dates.php
                        $dayData = $calendarDays[$dateStr] ?? null;
                        $isToday = $dateStr === $todayStr;
                    @endphp

                    @if ($isClosed && ! $dayData)
                        {{-- Closed (holiday / day off): greyed, labelled, no "add booking". --}}
                        <button type="button" x-on:click="openDay('{{ $dateStr }}')"
                             aria-label="{{ $cellDate->format('D j M') }}: closed" title="{{ $cellDate->format('D j M') }}: {{ $closedDates[$dateStr] }}"
                             class="rounded-md flex flex-col items-center justify-center gap-[2px] hover:ring-2 hover:ring-navy/40 transition-all"
                             style="{{ $isToday ? 'background:#081d3a;' : 'background:#eef0f4;' }}">
                            <span class="text-[11px] max-lg:text-[14px] leading-none {{ $isToday ? 'text-white font-semibold' : 'text-label line-through decoration-label/50' }}">{{ $day }}</span>
                            <span class="text-[8.5px] max-lg:text-[10px] font-semibold uppercase tracking-wide leading-none {{ $isToday ? 'text-white/80' : 'text-label' }}">Closed</span>
                        </button>
                    @elseif ($dayData)
                        @php
                            $tones = ['confirmed' => '#12b76a', 'waiting' => '#f79009', 'done' => '#98a2b3'];
                            $tip = $cellDate->format('D j M').":\n".collect($dayData['jobs'])->map(fn ($j) => $j['time'].' · '.$j['name'].' · '.$j['status'])->implode("\n");
                            $cellBg = $isToday ? '#081d3a' : ($dayData['upcoming'] ? ($dayData['allConfirmed'] ? '#e7f8ef' : '#fff4e0') : '#f6f7fa');
                            $cellRing = $isToday ? '' : ($dayData['upcoming'] ? ($dayData['allConfirmed'] ? 'box-shadow: inset 0 0 0 1px #a6e9c6;' : 'box-shadow: inset 0 0 0 1px #fbd38d;') : '');
                        @endphp
                        <button type="button" x-on:click="openDay('{{ $dateStr }}')"
                           title="{{ $tip }}" aria-label="{{ str_replace([":\n", "\n"], [': ', '; '], $tip) }}"
                           class="rounded-md flex flex-col items-center justify-center gap-[3px] relative hover:ring-2 hover:ring-navy transition-all {{ $dayData['upcoming'] || $isToday ? '' : 'opacity-70' }}"
                           style="background:{{ $cellBg }}; {{ $cellRing }}">
                            <span class="text-[11px] max-lg:text-[14px] font-semibold leading-none {{ $isToday ? 'text-white' : 'text-ink' }}">{{ $day }}</span>
                            {{-- Two bars: morning slot | afternoon slot. Empty = free. --}}
                            <span class="flex gap-[2px]" aria-hidden="true">
                                @foreach (['am', 'pm'] as $slotKey)
                                    @php
                                        $slotJob = $dayData['slots'][$slotKey];
                                    @endphp
                                    <span class="block h-[3px] max-lg:h-[4px] w-[9px] max-lg:w-[12px] rounded-full"
                                          style="background:{{ $slotJob ? ($isToday ? ($slotJob['tone'] === 'confirmed' ? '#f6e304' : '#ffffff') : $tones[$slotJob['tone']]) : ($isToday ? 'rgba(255,255,255,0.25)' : '#e4e7ec') }};"></span>
                                @endforeach
                            </span>
                        </button>
                    @else
                        <button type="button" x-on:click="openDay('{{ $dateStr }}')"
                             aria-label="{{ $cellDate->format('D j M') }}: no jobs"
                             class="rounded-md flex items-center justify-center hover:ring-2 hover:ring-navy/40 transition-all"
                             style="{{ $isToday ? 'background:#081d3a;' : ($isWeekend ? 'background:rgba(246,227,4,0.06);' : '') }}">
                            <span class="text-[11px] max-lg:text-[14px] {{ $isToday ? 'text-white font-semibold' : ($isWeekend ? 'text-ink' : 'text-label') }}">{{ $day }}</span>
                        </button>
                    @endif
                @endfor
            </div>

            <div class="flex items-center flex-wrap gap-x-3 gap-y-1 mt-2 pt-2 border-t border-line flex-shrink-0 text-label text-[10.5px] max-lg:text-[12px]">
                <span class="flex items-center gap-1.5"><span class="block h-[3px] w-[10px] rounded-full" style="background:#12b76a;"></span>Confirmed</span>
                <span class="flex items-center gap-1.5"><span class="block h-[3px] w-[10px] rounded-full" style="background:#f79009;"></span>Awaiting</span>
                <span class="flex items-center gap-1.5"><span class="block h-[3px] w-[10px] rounded-full" style="background:#98a2b3;"></span>Done</span>
                <span class="flex items-center gap-1.5"><span class="block h-[10px] w-[10px] rounded-sm" style="background:#eef0f4;"></span>Closed</span>
                <span class="ml-auto">Bars: AM | PM</span>
            </div>

            <template x-teleport="body">
                <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-2 sm:p-4"
                     role="dialog" aria-modal="true" :aria-label="'Jobs on ' + label">
                    <div x-show="open" x-transition.opacity.duration.200ms class="absolute inset-0" style="background:rgba(8,29,58,0.45);" x-on:click="close()"></div>
                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                         class="ios-sheet-panel relative bg-white rounded-2xl w-full max-w-md max-h-[85vh] overflow-y-auto"
                         style="box-shadow:0 24px 60px -16px rgba(8,29,58,0.45);">
                        <div class="flex items-start justify-between gap-3 px-5 pt-5 pb-3 border-b border-line">
                            <div>
                                <p class="text-label text-[11px] uppercase tracking-wider font-semibold">Jobs</p>
                                <h3 class="font-bold text-[16px] text-ink leading-tight mt-0.5" x-text="label"></h3>
                            </div>
                            <button type="button" x-ref="dayClose" x-on:click="close()" class="w-8 h-8 -mr-1 rounded-lg flex items-center justify-center text-label hover:text-ink hover:bg-canvas" aria-label="Close">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <div class="px-5 py-4 space-y-4">
                            <template x-for="slot in ['am', 'pm']" :key="slot">
                                <div>
                                    <p class="text-label text-[11.5px] font-semibold mb-1.5">
                                        <span x-text="slot === 'am' ? 'Morning' : 'Afternoon'"></span>
                                        &middot; <span x-text="slotLabels[slot]"></span>
                                    </p>
                                    <template x-for="(job, i) in jobsIn(slot)" :key="slot + i">
                                        <a :href="job.url" class="block border border-line rounded-xl px-3.5 py-3 mb-2 hover:border-navy transition-colors">
                                            <div class="flex items-start justify-between gap-3">
                                                <div class="min-w-0">
                                                    <p class="font-semibold text-[14px] text-ink truncate" x-text="job.name"></p>
                                                    <p class="text-muted text-[12.5px] truncate" x-text="job.service + (job.time !== slotLabels[slot] ? ' · ' + job.time : '')"></p>
                                                </div>
                                                <span class="flex-shrink-0 text-ink font-bold text-[13px]" x-text="job.value"></span>
                                            </div>
                                            <div class="flex items-center justify-between gap-3 mt-2">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold" :class="job.badge">
                                                    <span class="badge-dot" :class="job.dot"></span><span x-text="job.status"></span>
                                                </span>
                                                <span class="text-navy text-[12.5px] font-semibold">Open booking &rarr;</span>
                                            </div>
                                        </a>
                                    </template>
                                    <div x-show="jobsIn(slot).length === 0" class="flex items-center justify-between gap-3 border border-dashed border-line rounded-xl px-3.5 py-3">
                                        <span class="text-muted text-[13px]" x-text="isPast ? 'No job' : (closedReason ? 'Closed' : 'Free')"></span>
                                        <a x-show="isBookable" :href="createUrl" class="text-navy text-[12.5px] font-semibold hover:opacity-70">+ Add booking</a>
                                    </div>
                                </div>
                            </template>
                            <p x-show="!isPast && closedReason" class="text-label text-[12px]"><span class="font-semibold text-ink">Closed:</span> <span x-text="closedReason"></span>. Clients can't book this day. To reopen it, remove it from config/closed_dates.php.</p>
                            <p x-show="!isPast && !isBookable && !closedReason" class="text-label text-[12px]">Weekdays aren't open for bookings.</p>
                        </div>

                        <div class="px-5 pb-5" x-show="day && day.jobs.length">
                            <a :href="listUrl + '?date=' + open" class="text-navy text-[12.5px] font-semibold hover:opacity-70">See this day in the bookings list &rarr;</a>
                        </div>
                    </div>
                </div>
            </template>
        </div>

    </div>

@endsection
