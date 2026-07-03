<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BookingStatus;
use App\Enums\ExpenseCategory;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Expense;
use App\Support\Distance;
use App\Support\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    public function index(Request $request): View
    {
        $filtered = fn () => Expense::query()
            ->when($request->filled('category'), fn ($q) => $q->where('category', $request->query('category')))
            ->when($request->filled('from'), fn ($q) => $q->whereDate('date', '>=', $request->query('from')))
            ->when($request->filled('to'), fn ($q) => $q->whereDate('date', '<=', $request->query('to')))
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->query('search');
                $q->where(fn ($q) => $q->where('description', 'like', "%{$search}%")->orWhere('payee', 'like', "%{$search}%"));
            });

        $expenses = $filtered()
            ->with('booking')
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        $totalForFilter = $filtered()->sum('amount');

        return view('admin.expenses.index', [
            'expenses' => $expenses,
            'categories' => ExpenseCategory::cases(),
            'totalForFilter' => $totalForFilter,
        ]);
    }

    public function create(Request $request): View
    {
        $booking = $request->filled('booking_id') ? Booking::find($request->query('booking_id')) : null;

        return view('admin.expenses.create', [
            'categories' => ExpenseCategory::cases(),
            'booking' => $booking,
            'bookings' => $this->jobs($booking?->id),
            'suggestedDistanceKm' => $booking ? Distance::roundTripKm($booking->address, $booking->suburb) : null,
            'petrolPrice' => (float) Setting::get('fuel_petrol_price_per_litre', 23.50),
            'vehicleKmpl' => (float) Setting::get('fuel_vehicle_kmpl', 10),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        $data['created_by'] = $request->user()->id;

        Expense::create($data);

        return redirect()->route('admin.expenses.index')->with('status', 'Expense recorded.');
    }

    public function edit(Expense $expense): View
    {
        return view('admin.expenses.edit', [
            'expense' => $expense,
            'categories' => ExpenseCategory::cases(),
            'bookings' => $this->jobs($expense->booking_id),
            'petrolPrice' => (float) Setting::get('fuel_petrol_price_per_litre', 23.50),
            'vehicleKmpl' => (float) Setting::get('fuel_vehicle_kmpl', 10),
        ]);
    }

    public function update(Request $request, Expense $expense): RedirectResponse
    {
        $expense->update($this->validated($request));

        return redirect()->route('admin.expenses.index')->with('status', 'Expense updated.');
    }

    public function destroy(Expense $expense): RedirectResponse
    {
        $expense->delete();

        return back()->with('status', 'Expense removed.');
    }

    private function jobs(?int $mustInclude = null)
    {
        return Booking::query()
            ->where(function ($q) use ($mustInclude) {
                $q->whereIn('status', [BookingStatus::Accepted, BookingStatus::Completed]);

                if ($mustInclude) {
                    $q->orWhere('id', $mustInclude);
                }
            })
            ->orderByDesc('date')
            ->get(['id', 'name', 'date']);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'category' => ['required', Rule::enum(ExpenseCategory::class)],
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'booking_id' => 'nullable|exists:bookings,id',
            'payee' => 'nullable|string|max:255',
            'is_recurring' => 'nullable|boolean',
            'recurrence_note' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:2000',
        ]);
    }
}
