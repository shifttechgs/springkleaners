@extends('admin.layout')
@section('title', 'Expenses')
@section('content')

    <div class="flex items-start justify-between mb-8 flex-wrap gap-4">
        <div>
            <p class="text-label text-[11px] uppercase tracking-wider font-semibold mb-1">Money Out</p>
            <h1 class="text-[26px] font-extrabold tracking-tight leading-none">Expenses</h1>
        </div>
        <a href="{{ route('admin.expenses.create') }}" class="btn-primary">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Add Expense
        </a>
    </div>

    @if (session('status'))
        <div class="mb-6 px-4 py-3 bg-emerald-50 border border-emerald-100 rounded-xl text-emerald-700 text-[13px] font-medium">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 px-4 py-3 bg-rose-50 border border-rose-100 rounded-xl text-rose-600 text-[13px] font-medium">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="GET" class="flex flex-wrap gap-2.5 mb-6">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search description or payee..."
               class="bg-white border border-line rounded-xl px-4 py-2.5 text-[13px] text-ink placeholder-label focus:border-navy focus:outline-none transition-colors w-64">
        <select name="category" class="bg-white border border-line rounded-xl px-4 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
            <option value="">All categories</option>
            @foreach ($categories as $category)
                <option value="{{ $category->value }}" @selected(request('category') === $category->value)>{{ $category->label() }}</option>
            @endforeach
        </select>
        <input type="date" name="from" value="{{ request('from') }}" class="bg-white border border-line rounded-xl px-4 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
        <input type="date" name="to" value="{{ request('to') }}" class="bg-white border border-line rounded-xl px-4 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
        <button type="submit" class="btn-primary">Filter</button>
        @if (request()->anyFilled(['search', 'category', 'from', 'to']))
            <a href="{{ route('admin.expenses.index') }}" class="flex items-center text-muted font-semibold text-[13px] hover:text-navy transition-colors px-1">Clear</a>
        @endif
    </form>

    <div class="card p-5 mb-6 flex items-center justify-between">
        <p class="text-label text-[11px] uppercase tracking-wider font-semibold">Total for this filter</p>
        <p class="font-extrabold text-[22px] tracking-tight text-ink">R{{ number_format($totalForFilter, 2) }}</p>
    </div>

    <div class="card overflow-hidden">
        @if ($expenses->isEmpty())
            <div class="px-6 py-14 text-center">
                <p class="text-muted text-[13px]">No expenses match those filters.</p>
            </div>
        @else
            <table class="w-full data-table">
                <thead>
                    <tr>
                        <th class="px-6 py-3">Date</th>
                        <th class="px-6 py-3">Category</th>
                        <th class="px-6 py-3">Description</th>
                        <th class="px-6 py-3">Job</th>
                        <th class="px-6 py-3">Amount</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @foreach ($expenses as $expense)
                        <tr x-data="{ confirmOpen: false }">
                            <td class="px-6 py-3.5 text-muted">{{ $expense->date->format('d M Y') }}</td>
                            <td class="px-6 py-3.5">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11.5px] font-semibold {{ $expense->category->badgeColor() }}">
                                    {{ $expense->category->label() }}
                                </span>
                            </td>
                            <td class="px-6 py-3.5">
                                <p class="font-semibold leading-tight">{{ $expense->description }}</p>
                                @if ($expense->payee)
                                    <p class="text-label text-[12px] leading-tight mt-0.5">{{ $expense->payee }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-3.5 text-muted">
                                @if ($expense->booking)
                                    <a href="{{ route('admin.bookings.show', $expense->booking) }}" class="text-navy font-semibold hover:opacity-70 transition-opacity">{{ $expense->booking->name }}</a>
                                @else
                                    &mdash;
                                @endif
                            </td>
                            <td class="px-6 py-3.5 font-semibold text-ink">R{{ number_format((float) $expense->amount, 2) }}</td>
                            <td class="px-6 py-3.5 text-right">
                                <div class="flex items-center justify-end gap-4">
                                    <a href="{{ route('admin.expenses.edit', $expense) }}" class="text-navy font-semibold hover:opacity-70 transition-opacity">Edit</a>
                                    <button type="button" @click="confirmOpen = true" class="text-rose-500 font-semibold hover:opacity-70 transition-opacity">Delete</button>
                                </div>
                            </td>

                            <template x-teleport="body">
                                <div x-show="confirmOpen" x-cloak
                                     class="fixed inset-0 z-50 flex items-center justify-center p-4"
                                     style="background:rgba(8,29,58,0.45);"
                                     @click.self="confirmOpen = false">
                                    <div class="bg-white rounded-2xl p-6 w-full max-w-sm" style="box-shadow:0 20px 50px -12px rgba(0,0,0,0.4);">
                                        <h3 class="font-bold text-[16px] text-ink mb-2">Remove this expense?</h3>
                                        <p class="text-muted text-[13px] mb-5">{{ $expense->description }} — R{{ number_format((float) $expense->amount, 2) }}. This can't be undone.</p>
                                        <div class="flex items-center gap-3">
                                            <button type="button" @click="confirmOpen = false" class="flex-1 px-4 py-2.5 rounded-xl text-[13px] font-semibold border border-line text-ink hover:border-navy transition-colors">
                                                Cancel
                                            </button>
                                            <form method="POST" action="{{ route('admin.expenses.destroy', $expense) }}" class="flex-1">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full px-4 py-2.5 rounded-xl text-[13px] font-semibold bg-rose-500 text-white hover:bg-rose-600 transition-colors">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-6 py-4 border-t border-line">
                {{ $expenses->links() }}
            </div>
        @endif
    </div>

@endsection
