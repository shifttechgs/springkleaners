<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BookingStatus;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Client;
use App\Models\Expense;
use App\Support\QuoteExpiry;
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

        $upcomingJobs = Booking::where('status', BookingStatus::Accepted)
            ->where('date', '>=', $today)
            ->orderBy('date')
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

        $calendarMonth = $request->filled('month')
            ? Carbon::createFromFormat('Y-m', $request->query('month'))->startOfMonth()
            : now()->startOfMonth();

        $calendarDays = $this->calendarDays($calendarMonth);

        return view('admin.dashboard', [
            'calendarMonth' => $calendarMonth,
            'calendarDays' => $calendarDays,
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
     * @return array<string, array{accepted: int, other: int}>
     */
    private function calendarDays(Carbon $month): array
    {
        $bookings = Booking::whereBetween('date', [
            $month->copy()->startOfMonth()->toDateString(),
            $month->copy()->endOfMonth()->toDateString(),
        ])->get(['date', 'status']);

        $days = [];

        foreach ($bookings as $booking) {
            if ($booking->status === BookingStatus::Declined || $booking->status === BookingStatus::Expired) {
                continue;
            }

            $key = $booking->date->toDateString();
            $days[$key] ??= ['accepted' => 0, 'other' => 0];

            if ($booking->status === BookingStatus::Accepted || $booking->status === BookingStatus::Completed) {
                $days[$key]['accepted']++;
            } else {
                $days[$key]['other']++;
            }
        }

        return $days;
    }
}
