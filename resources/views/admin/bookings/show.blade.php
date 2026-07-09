@extends('admin.layout')
@section('title', $booking->name.' — Booking')
@section('content')

    @php
        $waNumber = preg_replace('/\D+/', '', $booking->phone);
        if (str_starts_with($waNumber, '0')) { $waNumber = '27'.substr($waNumber, 1); }
    @endphp

    <div class="flex items-center gap-2 text-label text-[12px] font-medium mb-6">
        <a href="{{ route('admin.bookings.index') }}" class="hover:text-navy transition-colors">Bookings</a>
        <span>/</span>
        <span class="text-ink">{{ $booking->name }}</span>
    </div>

    <div class="flex items-start justify-between mb-6 flex-wrap gap-4">
        <div class="flex items-center gap-4">
            <div class="avatar" style="width:44px;height:44px;border-radius:14px;font-size:16px;">{{ strtoupper(substr($booking->name, 0, 1)) }}</div>
            <div>
                <h1 class="text-[22px] font-extrabold tracking-tight leading-tight">{{ $booking->name }}</h1>
                <p class="text-muted text-[13px] mt-0.5">
                    {{ $booking->phone }} &middot; {{ $booking->date->format('d M Y') }} &middot; {{ $booking->time }}
                    @if ($booking->client)
                        &middot; <a href="{{ route('admin.clients.show', $booking->client) }}" class="text-navy font-semibold hover:opacity-70 transition-opacity">Client history &rarr;</a>
                    @endif
                </p>
            </div>
        </div>
        <x-admin.status-badge :status="$booking->status" />
    </div>

    {{-- Quick actions — always visible, no scrolling required --}}
    <div class="flex flex-wrap items-center gap-2 mb-8 pb-6 border-b border-line" x-data="{ copied: false }">

        <a href="{{ route('admin.bookings.quote-pdf.preview', $booking) }}" target="_blank" rel="noopener noreferrer"
           class="inline-flex items-center gap-2 bg-white border border-line text-ink font-semibold px-4 py-2.5 rounded-xl text-[13px] hover:border-navy transition-colors">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            Preview PDF
        </a>

        <a href="{{ route('admin.bookings.quote-pdf', $booking) }}"
           class="inline-flex items-center gap-2 bg-white border border-line text-ink font-semibold px-4 py-2.5 rounded-xl text-[13px] hover:border-navy transition-colors">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
            Download PDF
        </a>

        <a href="{{ route('admin.expenses.create', ['booking_id' => $booking->id]) }}"
           class="inline-flex items-center gap-2 bg-white border border-line text-ink font-semibold px-4 py-2.5 rounded-xl text-[13px] hover:border-navy transition-colors">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659a3 3 0 003.642 0l.879-.659M4.5 6.75h15M4.5 6.75a1.5 1.5 0 01-1.5-1.5V4.5A1.5 1.5 0 014.5 3h15a1.5 1.5 0 011.5 1.5v.75a1.5 1.5 0 01-1.5 1.5M4.5 6.75v12a1.5 1.5 0 001.5 1.5h12a1.5 1.5 0 001.5-1.5v-12"/></svg>
            Add Fuel Expense
        </a>

        @if ($booking->invoice_number)
            <a href="{{ route('admin.invoices.show', $booking) }}"
               class="inline-flex items-center gap-2 bg-white border border-line text-ink font-semibold px-4 py-2.5 rounded-xl text-[13px] hover:border-navy transition-colors">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25M9 15h3.75M9 18h3.75"/></svg>
                View Invoice — {{ $booking->invoice_number }}
            </a>
        @endif

        <div class="flex-1"></div>

        @if ($booking->status === \App\Enums\BookingStatus::Accepted)
            <form method="POST" action="{{ route('admin.bookings.update', $booking) }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="{{ \App\Enums\BookingStatus::Completed->value }}">
                <input type="hidden" name="quoted_price" value="{{ $booking->quoted_price }}">
                <input type="hidden" name="admin_notes" value="{{ $booking->admin_notes }}">
                <button type="submit" class="inline-flex items-center gap-2 bg-white border border-line text-ink font-semibold px-4 py-2.5 rounded-xl text-[13px] hover:border-navy transition-colors">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Mark as Completed
                </button>
            </form>
        @endif

        @if ($booking->accepted_token)
            @php
                $quoteUrl = route('quote.show', $booking->accepted_token);
                $message = "Hi {$booking->name}! Here's your SpringKleaners quote for ".(\App\Support\Services::find($booking->service)['name'] ?? $booking->service).": R".number_format((float) $booking->quoted_price, 2)."\n\nView & confirm here: {$quoteUrl}";
            @endphp
            <button type="button" @click="navigator.clipboard.writeText('{{ $quoteUrl }}'); copied = true; setTimeout(() => copied = false, 2000)"
                    class="inline-flex items-center gap-2 bg-white border border-line text-ink font-semibold px-4 py-2.5 rounded-xl text-[13px] hover:border-navy transition-colors">
                <svg x-show="!copied" class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                <svg x-show="copied" x-cloak class="w-4 h-4 flex-shrink-0 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                <span x-text="copied ? 'Copied!' : 'Copy Link'"></span>
            </button>
            <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode($message) }}" target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center gap-2 bg-[#25d366] text-white font-semibold px-4 py-2.5 rounded-xl text-[13px] hover:bg-[#20bd5a] transition-colors">
                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347M12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0012.05 0"/></svg>
                Send via WhatsApp
            </a>
        @else
            <form method="POST" action="{{ route('admin.bookings.send-quote', $booking) }}">
                @csrf
                <input type="hidden" name="quoted_price" value="{{ $booking->quoted_price ?? $booking->total }}">
                <button type="submit" class="inline-flex items-center gap-2 bg-[#f6e304] text-[#081d3a] font-bold px-5 py-2.5 rounded-xl text-[13px] hover:bg-yellow-300 transition-colors">
                    Send Quote — R{{ number_format((float) ($booking->quoted_price ?? $booking->total ?? 0), 2) }}
                </button>
            </form>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 space-y-6">

            {{-- Quote breakdown --}}
            <div class="card p-6">
                @include('partials.quote-breakdown', ['booking' => $booking])
            </div>

            {{-- Expenses on this job --}}
            <div class="card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-bold text-[14px] tracking-tight">Expenses on this job</h2>
                    <a href="{{ route('admin.expenses.create', ['booking_id' => $booking->id]) }}" class="text-navy text-[12.5px] font-semibold hover:opacity-70 transition-opacity">+ Add expense</a>
                </div>

                @if ($booking->expenses->isEmpty())
                    <p class="text-muted text-[13px]">No expenses logged against this job yet.</p>
                @else
                    <div class="divide-y divide-line">
                        @foreach ($booking->expenses as $expense)
                            <div class="flex items-center justify-between py-2.5 first:pt-0 last:pb-0">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <span class="w-2 h-2 rounded-full flex-shrink-0 {{ $expense->category->dotColor() }}"></span>
                                    <div class="min-w-0">
                                        <p class="text-ink text-[13px] font-semibold truncate">{{ $expense->description }}</p>
                                        <p class="text-muted text-[11.5px]">{{ $expense->category->label() }} &middot; {{ $expense->date->format('d M Y') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 flex-shrink-0">
                                    <span class="text-ink text-[13px] font-bold">R{{ number_format((float) $expense->amount, 2) }}</span>
                                    <a href="{{ route('admin.expenses.edit', $expense) }}" class="text-label hover:text-navy transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @php
                        $jobRevenue = (float) ($booking->quoted_price ?? $booking->total ?? 0);
                        $jobExpenses = (float) $booking->expenses->sum('amount');
                        $jobNet = $jobRevenue - $jobExpenses;
                    @endphp
                    <div class="flex items-center justify-between pt-3 mt-1 border-t border-line">
                        <span class="text-label text-[11px] uppercase tracking-wider font-semibold">Net for this job</span>
                        <span class="text-[14px] font-extrabold {{ $jobNet >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">R{{ number_format($jobNet, 2) }}</span>
                    </div>
                @endif
            </div>

        </div>

        {{-- Admin actions --}}
        <div class="space-y-6">

            <div class="card p-6">
                <h2 class="font-bold text-[14px] tracking-tight mb-4">Update Status</h2>
                <form method="POST" action="{{ route('admin.bookings.update', $booking) }}" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label class="block text-label text-[11px] uppercase tracking-wider mb-1.5 font-semibold">Status</label>
                        <select name="status" class="w-full bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
                            @foreach ($statuses as $status)
                                <option value="{{ $status->value }}" @selected($booking->status === $status)>{{ $status->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-label text-[11px] uppercase tracking-wider mb-1.5 font-semibold">Quoted Price (R)</label>
                        <input type="number" step="0.01" min="0" name="quoted_price" value="{{ $booking->quoted_price }}"
                               class="w-full bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
                    </div>
                    <div>
                        <label class="block text-label text-[11px] uppercase tracking-wider mb-1.5 font-semibold">Admin Notes</label>
                        <textarea name="admin_notes" rows="3"
                                  class="w-full bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors resize-none">{{ $booking->admin_notes }}</textarea>
                    </div>
                    <button type="submit" class="btn-primary w-full justify-center">Save Changes</button>
                </form>
            </div>

            <div class="card p-6">
                <h2 class="font-bold text-[14px] tracking-tight mb-1">Custom Quote Price</h2>
                <p class="text-muted text-[12.5px] mb-4">Override the auto-estimated price before sending — the "Send Quote" button above uses this once set.</p>
                <form method="POST" action="{{ route('admin.bookings.send-quote', $booking) }}" class="space-y-3">
                    @csrf
                    <input type="number" step="0.01" min="0" name="quoted_price" placeholder="Final price (R)"
                           value="{{ $booking->quoted_price ?? $booking->total }}" required
                           class="w-full bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
                    <div>
                        <label class="block text-label text-[11px] uppercase tracking-wider mb-1.5 font-semibold">Deposit Required (R) <span class="normal-case font-normal text-label">(optional)</span></label>
                        <input type="number" step="0.01" min="0" name="deposit_amount" placeholder="Leave blank for no deposit"
                               value="{{ $booking->deposit_amount }}"
                               class="w-full bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
                    </div>
                    <button type="submit" class="btn-gold w-full justify-center">
                        {{ $booking->accepted_token ? 'Update Price & Regenerate Link' : 'Set Price & Send Quote' }}
                    </button>
                </form>
                @if ($booking->deposit_amount > 0 && ! $booking->deposit_paid_at)
                    <form method="POST" action="{{ route('admin.invoices.mark-deposit-paid', $booking) }}" class="mt-3">
                        @csrf
                        <button type="submit" class="w-full justify-center inline-flex items-center gap-2 bg-white border border-line text-ink font-semibold px-4 py-2.5 rounded-xl text-[13px] hover:border-navy transition-colors">
                            Mark Deposit as Received
                        </button>
                    </form>
                @elseif ($booking->deposit_paid_at)
                    <p class="text-emerald-600 text-[12.5px] font-semibold mt-3">Deposit received {{ $booking->deposit_paid_at->format('d M Y') }}.</p>
                @endif
            </div>

            @if ($booking->status === \App\Enums\BookingStatus::Accepted)
                <div class="card p-6" x-data="{ copied: false }">
                    <h2 class="font-bold text-[14px] tracking-tight mb-1">Booking Confirmation</h2>
                    <p class="text-muted text-[12.5px] mb-4">Let the client know their booking is locked in.</p>
                    @php
                        $confirmMessage = "Hi {$booking->name}! Your SpringKleaners booking is confirmed for ".$booking->date->format('d M Y')." at {$booking->time}.".($booking->deposit_amount > 0 && ! $booking->deposit_paid_at ? "\n\nDeposit due: R".number_format((float) $booking->deposit_amount, 2)." — ".\App\Support\Company::bankName().", Branch Code ".\App\Support\Company::branchCode().", Account No ".\App\Support\Company::accountNo().". ".\App\Support\Company::referenceNote() : "")."\n\nSee you then!";
                    @endphp
                    <div class="flex flex-wrap gap-2">
                        <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode($confirmMessage) }}" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-2 bg-[#25d366] text-white font-semibold px-4 py-2.5 rounded-xl text-[13px] hover:bg-[#20bd5a] transition-colors">
                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347M12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0012.05 0"/></svg>
                            WhatsApp
                        </a>
                        <form method="POST" action="{{ route('admin.bookings.send-confirmation-email', $booking) }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 bg-white border border-line text-ink font-semibold px-4 py-2.5 rounded-xl text-[13px] hover:border-navy transition-colors">
                                Email
                            </button>
                        </form>
                    </div>
                    @if ($booking->confirmation_sent_at)
                        <p class="text-muted text-[11.5px] mt-2">Emailed {{ $booking->confirmation_sent_at->format('d M Y H:i') }}.</p>
                    @endif
                </div>
            @endif

            @if ($booking->status === \App\Enums\BookingStatus::Completed)
                <div class="card p-6">
                    <h2 class="font-bold text-[14px] tracking-tight mb-1">Ask for a Review</h2>
                    <p class="text-muted text-[12.5px] mb-4">Send this the moment the job wraps up, independent of payment status — review velocity matters more to local search ranking than total review count, and satisfaction is highest right after the clean.</p>
                    @php
                        $reviewMessage = "Thank you for choosing SpringKleaners, {$booking->name}! We'd love a quick review: ".\App\Support\Company::reviewUrl();
                    @endphp
                    <div class="flex flex-wrap gap-2">
                        <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode($reviewMessage) }}" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-2 bg-[#25d366] text-white font-semibold px-4 py-2.5 rounded-xl text-[13px] hover:bg-[#20bd5a] transition-colors">
                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347M12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0012.05 0"/></svg>
                            WhatsApp
                        </a>
                        <form method="POST" action="{{ route('admin.bookings.send-thank-you-email', $booking) }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 bg-white border border-line text-ink font-semibold px-4 py-2.5 rounded-xl text-[13px] hover:border-navy transition-colors">
                                Email
                            </button>
                        </form>
                    </div>
                    @if ($booking->thank_you_sent_at)
                        <p class="text-muted text-[11.5px] mt-2">Emailed {{ $booking->thank_you_sent_at->format('d M Y H:i') }}.</p>
                    @endif
                </div>
            @endif

        </div>
    </div>

@endsection
