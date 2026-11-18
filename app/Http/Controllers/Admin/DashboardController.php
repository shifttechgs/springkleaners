<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BookingStatus;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Client;
use App\Models\Expense;
use App\Support\QuoteExpiry;
use App\Support\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        QuoteExpiry::sweep();

        $today = now()->toDateString();
        $tomorrow = now()->addDay()->toDateString();

        $monthRevenue = $this->revenueFor(now()->startOfMonth(), now()->endOfMonth());
        $lastMonthRevenue = $this->revenueFor(now()->subMonthNoOverflow()->startOfMonth(), now()->subMonthNoOverflow()->endOfMonth());

        $revenueTrend = $lastMonthRevenue > 0
            ? round((($monthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100)
            : null;

        $monthExpenses = $this->expensesFor(now()->startOfMonth(), now()->endOfMonth());
        $netProfit = $monthRevenue - $monthExpenses;
        $profitMargin = $monthRevenue > 0 ? round(($netProfit / $monthRevenue) * 100) : null;

        $bookedRevenue = (float) Booking::where('status', BookingStatus::Accepted)
            ->where('date', '>=', $today)
            ->get()
            ->sum(fn (Booking $b) => (float) ($b->quoted_price ?? $b->total ?? 0));

        $completedThisMonthCount = Booking::where('status', BookingStatus::Completed)
            ->whereBetween('date', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])
            ->count();

        $avgJobValue = $completedThisMonthCount > 0 ? $monthRevenue / $completedThisMonthCount : 0;

        $quotedEver = Booking::whereNotNull('quote_sent_at')->count();
        $won = Booking::whereIn('status', [BookingStatus::Accepted, BookingStatus::Completed])->count();
        $winRate = $quotedEver > 0 ? round(($won / $quotedEver) * 100) : null;

        $counts = Booking::query()
            ->selectRaw('status, count(*) as total, coalesce(sum(quoted_price), sum(total)) as value')
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        $pendingCount = (int) ($counts[BookingStatus::Pending->value]->total ?? 0);
        $quotedCount = (int) ($counts[BookingStatus::Quoted->value]->total ?? 0);
        $quotedValue = (float) ($counts[BookingStatus::Quoted->value]->value ?? 0);
        $acceptedCount = (int) ($counts[BookingStatus::Accepted->value]->total ?? 0);
        $acceptedValue = (float) ($counts[BookingStatus::Accepted->value]->value ?? 0);

        $staleQuotes = Booking::where('status', BookingStatus::Quoted)
            ->where('quote_sent_at', '<=', now()->subDays(3))
            ->orderBy('quote_sent_at')
            ->get();

        $jobsToday = Booking::where('status', BookingStatus::Accepted)->whereDate('date', $today)->count();
        $jobsTomorrow = Booking::where('status', BookingStatus::Accepted)->whereDate('date', $tomorrow)->count();

        $overdueJobs = Booking::where('status', BookingStatus::Accepted)
            ->where('date', '<', $today)
            ->count();

        // Everything still on the schedule from today on — confirmed jobs and ones awaiting a quote or the
        // client's go-ahead (they hold the slot too). Each row shows its status.
        $upcomingQuery = Booking::whereIn('status', BookingStatus::scheduled())->where('date', '>=', $today);
        $upcomingCount = (clone $upcomingQuery)->count();

        $upcomingJobs = $upcomingQuery
            ->orderBy('date')
            // Morning slot before afternoon: times are text, and "1:00 PM – 5:00 PM" would sort before "8:00 AM – 12:00 PM".
            ->orderByRaw("CASE WHEN time LIKE '%AM%' THEN 0 ELSE 1 END")
            ->orderBy('time')
            ->limit(8)
            ->get();

        $revenueHistory = collect(range(5, 0))
            ->map(function (int $monthsAgo) {
                $month = now()->subMonthsNoOverflow($monthsAgo);

                return $this->revenueFor($month->copy()->startOfMonth(), $month->copy()->endOfMonth());
            })
            ->values();

        $expenseHistory = collect(range(5, 0))
            ->map(function (int $monthsAgo) {
                $month = now()->subMonthsNoOverflow($monthsAgo);

                return $this->expensesFor($month->copy()->startOfMonth(), $month->copy()->endOfMonth());
            })
            ->values();

        $calendarMonth = $this->calendarMonth($request->query('month'));
        $calendarDays = $this->calendarDays($calendarMonth);

        return view('admin.dashboard', [
            'calendarMonth' => $calendarMonth,
            'calendarDays' => $calendarDays,
            'nextJob' => $upcomingJobs->first(),
            'monthRevenue' => $monthRevenue,
            'revenueTrend' => $revenueTrend,
            'revenueHistory' => $revenueHistory,
            'monthExpenses' => $monthExpenses,
            'netProfit' => $netProfit,
            'profitMargin' => $profitMargin,
            'expenseHistory' => $expenseHistory,
            'bookedRevenue' => $bookedRevenue,
            'avgJobValue' => $avgJobValue,
            'winRate' => $winRate,
            'pendingCount' => $pendingCount,
            'quotedCount' => $quotedCount,
            'quotedValue' => $quotedValue,
            'acceptedCount' => $acceptedCount,
            'acceptedValue' => $acceptedValue,
            'staleQuotes' => $staleQuotes,
            'jobsToday' => $jobsToday,
            'jobsTomorrow' => $jobsTomorrow,
            'overdueJobs' => $overdueJobs,
            'upcomingJobs' => $upcomingJobs,
            'upcomingCount' => $upcomingCount,
            'clientCount' => Client::count(),
            'newClientsThisMonth' => Client::where('created_at', '>=', now()->startOfMonth())->count(),
        ]);
    }

    private function revenueFor(Carbon $start, Carbon $end): float
    {
        return (float) Booking::where('status', BookingStatus::Completed)
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->get()
            ->sum(fn (Booking $b) => (float) ($b->quoted_price ?? $b->total ?? 0));
    }

    private function expensesFor(Carbon $start, Carbon $end): float
    {
        return (float) Expense::whereBetween('date', [$start->toDateString(), $end->toDateString()])->sum('amount');
    }

    /**
     * The month shown on the dashboard calendar (?month=YYYY-MM). "!" pins the day to the 1st — without it,
     * on the 29th–31st a short month like February overflows into March. Bad input falls back to this month.
     */
    private function calendarMonth(?string $month): Carbon
    {
        if (is_string($month) && preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $month)) {
            return Carbon::createFromFormat('!Y-m', $month)->startOfMonth();
        }

        return now()->startOfMonth();
    }

    /**
     * Jobs per day for the dashboard calendar, split into the two slots so each day can show which are taken.
     * Declined and expired bookings don't hold a slot, so they're left off.
     *
     * @return array<string, array{upcoming: bool, allConfirmed: bool, slots: array{am: ?array, pm: ?array}, jobs: array<int, array{name: string, time: string, status: string, tone: string}>}>
     */
    private function calendarDays(Carbon $month): array
    {
        $bookings = Booking::whereBetween('date', [
            $month->copy()->startOfMonth()->toDateString(),
            $month->copy()->endOfMonth()->toDateString(),
        ])
            ->whereIn('status', BookingStatus::occupyingSlot())
            ->orderByRaw("CASE WHEN time LIKE '%AM%' THEN 0 ELSE 1 END")
            ->orderBy('time')
            ->get(['id', 'date', 'time', 'status', 'name', 'service', 'quoted_price', 'total']);

        $today = now()->toDateString();
        $services = Services::list();
        $days = [];

        foreach ($bookings as $booking) {
            $key = $booking->date->toDateString();
            $days[$key] ??= ['upcoming' => $key >= $today, 'allConfirmed' => true, 'slots' => ['am' => null, 'pm' => null], 'jobs' => []];

            // green = confirmed, amber = waiting on a quote or the client, grey = done
            $tone = match ($booking->status) {
                BookingStatus::Accepted => 'confirmed',
                BookingStatus::Completed => 'done',
                default => 'waiting',
            };

            $job = [
                'name' => $booking->name,
                'time' => $booking->time,
                'status' => $booking->status->label(),
                'tone' => $tone,
                // for the day panel opened by clicking the calendar
                'service' => $services[$booking->service]['name'] ?? $booking->service,
                'value' => 'R'.number_format((float) ($booking->quoted_price ?? $booking->total ?? 0), 0),
                'badge' => $booking->status->badgeColor(),
                'dot' => $booking->status->dotColor(),
                'url' => route('admin.bookings.show', $booking),
            ];
            $slot = Booking::slotFor($booking->time) === Booking::MORNING_SLOT ? 'am' : 'pm';
            $job['slot'] = $slot;

            $days[$key]['jobs'][] = $job;
            $days[$key]['slots'][$slot] ??= $job;

            if ($tone === 'waiting') {
                $days[$key]['allConfirmed'] = false;
            }
        }

        return $days;
    }
}
