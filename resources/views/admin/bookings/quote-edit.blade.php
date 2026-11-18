@extends('admin.layout')
@php
    // Same editor for quotes and unpaid invoices.
    $isInvoice = $booking->isInvoiced();
    $docWord = $isInvoice ? 'invoice' : 'quote';
    $backUrl = $isInvoice ? route('admin.invoices.show', $booking) : route('admin.bookings.show', $booking);
@endphp
@section('title', 'Edit '.$docWord.' — '.$booking->name)
@section('navTitle', 'Edit '.$docWord)
@section('back', $backUrl)
@section('backLabel', $isInvoice ? 'Invoice' : 'Booking')
@section('content')

    @php
        $startItems = old('items', array_map(fn ($item) => [
            'description' => $item['description'],
            'note' => $item['note'] ?? '',
            'qty' => $item['qty'],
            'unit_price' => $item['unit_price'],
        ], $items));
        $wasAccepted = ! $isInvoice && $booking->status === \App\Enums\BookingStatus::Accepted;
        $depositLocked = (bool) $booking->deposit_paid_at;
        $acceptedTotal = (float) ($booking->quoted_price ?? $booking->total ?? 0);
    @endphp

    <div class="hidden lg:flex items-center gap-2 text-label text-[12px] font-medium mb-6">
        <a href="{{ route('admin.bookings.index') }}" class="hover:text-navy transition-colors">Bookings</a>
        <span>/</span>
        <a href="{{ route('admin.bookings.show', $booking) }}" class="hover:text-navy transition-colors">{{ $booking->name }}</a>
        <span>/</span>
        <span class="text-ink">Edit {{ $docWord }}</span>
    </div>

    <div class="flex items-start justify-between mb-6 flex-wrap gap-4">
        <div>
            <p class="text-label text-[11px] uppercase tracking-wider font-semibold mb-1">{{ $isInvoice ? 'Invoice '.$booking->invoice_number : 'Quote SK-'.str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</p>
            <h1 class="text-[26px] font-extrabold tracking-tight leading-none">Edit {{ $docWord }}</h1>
            <p class="text-muted text-[13px] mt-2">{{ $booking->name }} &middot; {{ \App\Support\ServiceSnapshot::for($booking)['name'] }} &middot; {{ $booking->date->format('d M Y') }}</p>
        </div>
        <x-admin.status-badge :status="$booking->status" />
    </div>

    @if ($errors->any())
        <div class="mb-6 px-4 py-3 bg-rose-50 border border-rose-100 rounded-xl text-rose-600 text-[13px] font-medium">
            {{ $errors->first() }}
        </div>
    @endif

    @if ($isInvoice)
        <div class="mb-6 px-4 py-3 bg-amber-50 border border-amber-100 rounded-xl text-amber-800 text-[13px]">
            <strong>This invoice has been issued but not paid yet.</strong>
            Saving corrects it and marks it <strong>Revised</strong> on the PDF and the client's invoice page — then send it to the client again.
            Once it's marked paid it can't be changed.
        </div>
    @elseif ($wasAccepted)
        <div class="mb-6 px-4 py-3 bg-amber-50 border border-amber-100 rounded-xl text-amber-800 text-[13px]">
            <strong>The client already accepted R{{ number_format($acceptedTotal, 2) }}.</strong>
            If you change the total, the booking goes back to "Quoted" and they confirm the new price on the same link.
        </div>
    @elseif ($booking->accepted_token)
        <div class="mb-6 px-4 py-3 bg-[#f4f6fb] border border-line rounded-xl text-muted text-[13px]">
            This quote has been sent. Saving updates what the client sees on their quote link and PDF straight away.
        </div>
    @endif

    <form method="POST" action="{{ route('admin.bookings.quote.update', $booking) }}"
          x-data="{
              items: {{ \Illuminate\Support\Js::from($startItems) }},
              deposit: {{ \Illuminate\Support\Js::from(old('deposit_amount', $booking->deposit_amount !== null ? (float) $booking->deposit_amount : '')) }},
              maxLines: {{ \App\Http\Controllers\Admin\QuoteEditorController::MAX_LINES }},
              money(n) { const v = Math.round(n * 100) / 100; return (v < 0 ? '−R' : 'R') + Math.abs(v).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) },
              lineTotal(item) { return (parseInt(item.qty, 10) || 0) * (parseFloat(item.unit_price) || 0) },
              get total() { return Math.round(this.items.reduce((sum, item) => sum + this.lineTotal(item), 0) * 100) / 100 },
              addLine(kind) {
                  if (this.items.length >= this.maxLines) return;
                  this.items.push(kind === 'discount'
                      ? { description: 'Discount', note: '', qty: 1, unit_price: -100 }
                      : { description: '', note: '', qty: 1, unit_price: 0 });
                  this.$nextTick(() => { const rows = this.$refs.lines.querySelectorAll('[data-line] input[data-desc]'); rows[rows.length - 1]?.focus(); });
              },
              removeLine(i) { if (this.items.length > 1) this.items.splice(i, 1) },
              moveLine(i, dir) { const j = i + dir; if (j < 0 || j >= this.items.length) return; [this.items[i], this.items[j]] = [this.items[j], this.items[i]] },
          }">
        @csrf
        @method('PUT')

        {{-- Service date & time: same rules as bookings (weekends only, two slots, one booking per slot). --}}
        @php($originalSlot = \App\Models\Booking::slotFor($booking->time))
        <div class="card p-4 sm:p-6 mb-6"
             x-data="{
                 date: {{ \Illuminate\Support\Js::from(old('date', $booking->date->toDateString())) }},
                 slot: {{ \Illuminate\Support\Js::from(old('time', $originalSlot)) }},
                 slots: {{ \Illuminate\Support\Js::from(\App\Models\Booking::TIME_SLOTS) }},
                 originalDate: {{ \Illuminate\Support\Js::from($booking->date->toDateString()) }},
                 originalSlot: {{ \Illuminate\Support\Js::from($originalSlot) }},
                 bookableDays: {{ \Illuminate\Support\Js::from(\App\Models\Booking::BOOKABLE_DAYS) }},
                 today: {{ \Illuminate\Support\Js::from(now()->toDateString()) }},
                 url: {{ \Illuminate\Support\Js::from(route('booking.availability')) }},
                 taken: {},
                 month: null,
                 init() { this.load(); this.$watch('date', () => this.load()); },
                 async load() {
                     if (!this.date) return;
                     const m = this.date.slice(0, 7) + '-01';
                     if (m === this.month) return;
                     this.month = m;
                     try { const res = await fetch(this.url + '?month=' + m); const json = await res.json(); this.taken = json.times || {}; } catch (e) {}
                 },
                 get moved() { return this.date !== this.originalDate || this.slot !== this.originalSlot },
                 get isPast() { return this.moved && this.date < this.today },
                 get isClosedDay() { if (!this.date) return false; const d = new Date(this.date + 'T00:00:00'); return this.moved && !this.bookableDays.includes(d.getDay()); },
                 isTaken(s) {
                     if (this.date === this.originalDate && s === this.originalSlot) return false;
                     return (this.taken[this.date] || []).includes(s);
                 },
                 get label() { if (!this.date) return ''; return new Date(this.date + 'T00:00:00').toLocaleDateString('en-GB', { weekday: 'short', day: 'numeric', month: 'short', year: 'numeric' }); },
             }">
            <h2 class="font-bold text-[14px] tracking-tight">Service date &amp; time</h2>
            <p class="text-label text-[12px] mt-0.5 mb-4">
                @if ($isInvoice)
                    Correct the date the job was done if it's wrong on the invoice. Another job's slot still can't be taken.
                @else
                    Weekends only, two slots a day, one booking per slot.
                @endif
            </p>

            <div class="grid grid-cols-1 md:grid-cols-[220px_1fr] gap-4 items-start">
                <label class="block">
                    <span class="block text-label text-[11px] uppercase tracking-wider mb-1.5 font-semibold">Date</span>
                    <input type="date" name="date" x-model="date" required
                           class="w-full bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
                </label>
                <div>
                    <span class="block text-label text-[11px] uppercase tracking-wider mb-1.5 font-semibold">Slot</span>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <template x-for="(s, i) in slots" :key="s">
                            <label class="flex items-center justify-between gap-3 border rounded-xl px-3.5 py-2.5 cursor-pointer transition-colors"
                                   :class="slot === s ? 'border-navy bg-[#f4f6fb]' : (isTaken(s) ? 'border-line bg-[#fafbfc] cursor-not-allowed opacity-60' : 'border-line hover:border-navy')">
                                <span class="flex items-center gap-2.5">
                                    <input type="radio" name="time" :value="s" x-model="slot" :disabled="isTaken(s) && slot !== s" class="accent-[#081d3a]">
                                    <span>
                                        <span class="block text-[13px] font-bold text-ink" x-text="i === 0 ? 'Morning' : 'Afternoon'"></span>
                                        <span class="block text-[12px] text-muted" x-text="s"></span>
                                    </span>
                                </span>
                                <span class="text-[10.5px] font-bold px-2 py-0.5 rounded-full"
                                      :class="isTaken(s) ? 'bg-rose-50 text-rose-600' : 'bg-emerald-50 text-emerald-700'"
                                      x-text="isTaken(s) ? 'Booked' : 'Available'"></span>
                            </label>
                        </template>
                    </div>
                </div>
            </div>

            <p x-show="isPast" x-cloak class="mt-3 text-rose-600 text-[12.5px] font-semibold">That date has passed — pick today or later.</p>
            <p x-show="isClosedDay && !isPast" x-cloak class="mt-3 text-rose-600 text-[12.5px] font-semibold">We only take bookings on Saturdays and Sundays.</p>
            <p x-show="moved && !isPast && !isClosedDay" x-cloak class="mt-3 text-[12.5px] text-muted">
                Moving to <strong class="text-ink" x-text="label + ', ' + slot"></strong> from {{ $booking->date->format('D j M Y') }}, {{ $booking->time }}.
                @if ($booking->status === \App\Enums\BookingStatus::Accepted)
                    Let the client know — you can resend the booking confirmation afterwards.
                @endif
            </p>
        </div>

        <div class="card overflow-hidden mb-6">
            <div class="px-4 sm:px-6 py-4 border-b border-line flex items-center justify-between gap-3 flex-wrap">
                <div>
                    <h2 class="font-bold text-[14px] tracking-tight">Quote lines</h2>
                    <p class="text-label text-[12px] mt-0.5">
                        {{ $isCustom ? 'Edited by hand.' : 'Worked out from the service and add-on prices.' }}
                        Use a negative unit price for a discount.
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" @click="addLine('item')" :disabled="items.length >= maxLines"
                            class="inline-flex items-center gap-1.5 bg-white border border-line text-ink font-semibold px-3.5 py-2 rounded-xl text-[13px] hover:border-navy transition-colors disabled:opacity-40">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Add line
                    </button>
                    <button type="button" @click="addLine('discount')" :disabled="items.length >= maxLines"
                            class="inline-flex items-center gap-1.5 bg-white border border-line text-ink font-semibold px-3.5 py-2 rounded-xl text-[13px] hover:border-navy transition-colors disabled:opacity-40">
                        Add discount
                    </button>
                </div>
            </div>

            {{-- Column headings (desktop). On phones each line stacks into its own block. --}}
            <div class="hidden md:grid grid-cols-[1fr_76px_120px_110px_76px] gap-3 px-6 py-2.5 text-label text-[11px] uppercase tracking-wider font-semibold border-b border-line bg-[#fafbfc]">
                <span>Description</span><span class="text-right">Qty</span><span class="text-right">Unit price (R)</span><span class="text-right">Line total</span><span></span>
            </div>

            <div x-ref="lines" class="divide-y divide-line">
                <template x-for="(item, i) in items" :key="i">
                    <div data-line class="grid grid-cols-2 md:grid-cols-[1fr_76px_120px_110px_76px] gap-3 px-4 sm:px-6 py-3.5 items-start">
                        <div class="col-span-2 md:col-span-1 space-y-1.5">
                            <input type="text" data-desc :name="'items[' + i + '][description]'" x-model="item.description" required maxlength="255"
                                   placeholder="What the client is paying for" aria-label="Description"
                                   class="w-full bg-white border border-line rounded-lg px-3 py-2 text-[13px] font-semibold text-ink focus:border-navy focus:outline-none">
                            <input type="text" :name="'items[' + i + '][note]'" x-model="item.note" maxlength="500"
                                   placeholder="Note under the line (optional)" aria-label="Note"
                                   class="w-full bg-white border border-line rounded-lg px-3 py-1.5 text-[12px] text-muted focus:border-navy focus:outline-none">
                        </div>
                        <label class="block">
                            <span class="md:hidden block text-label text-[11px] font-semibold mb-1">Qty</span>
                            <input type="number" :name="'items[' + i + '][qty]'" x-model="item.qty" required min="1" max="999" step="1" inputmode="numeric" aria-label="Quantity"
                                   class="w-full bg-white border border-line rounded-lg px-3 py-2 text-[13px] text-ink text-right focus:border-navy focus:outline-none">
                        </label>
                        <label class="block">
                            <span class="md:hidden block text-label text-[11px] font-semibold mb-1">Unit price (R)</span>
                            <input type="number" :name="'items[' + i + '][unit_price]'" x-model="item.unit_price" required step="0.01" inputmode="decimal" aria-label="Unit price in rand"
                                   class="w-full bg-white border border-line rounded-lg px-3 py-2 text-[13px] text-right focus:border-navy focus:outline-none"
                                   :class="parseFloat(item.unit_price) < 0 ? 'text-emerald-700' : 'text-ink'">
                        </label>
                        <div class="md:pt-2 text-right">
                            <span class="md:hidden block text-label text-[11px] font-semibold mb-1">Line total</span>
                            <span class="text-[13px] font-bold" :class="lineTotal(item) < 0 ? 'text-emerald-700' : 'text-ink'" x-text="money(lineTotal(item))"></span>
                        </div>
                        <div class="flex items-center justify-end gap-1 md:pt-1">
                            <button type="button" @click="moveLine(i, -1)" :disabled="i === 0" class="w-7 h-7 rounded-lg text-label hover:text-navy hover:bg-[#f4f6fb] disabled:opacity-30" aria-label="Move line up">
                                <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                            </button>
                            <button type="button" @click="moveLine(i, 1)" :disabled="i === items.length - 1" class="w-7 h-7 rounded-lg text-label hover:text-navy hover:bg-[#f4f6fb] disabled:opacity-30" aria-label="Move line down">
                                <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <button type="button" @click="removeLine(i)" :disabled="items.length === 1" class="w-7 h-7 rounded-lg text-rose-500 hover:bg-rose-50 disabled:opacity-30" aria-label="Remove line">
                                <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>
                </template>
            </div>

            <div class="px-4 sm:px-6 py-4 border-t border-line bg-[#fafbfc] flex items-center justify-between gap-4">
                <span class="text-ink text-[14px] font-bold">{{ ucfirst($docWord) }} total</span>
                <span class="text-[22px] font-extrabold tracking-tight" :class="total < 0 ? 'text-rose-600' : 'text-ink'" x-text="money(total)"></span>
            </div>
            <p x-show="total < 0" x-cloak class="px-6 pb-4 bg-[#fafbfc] text-rose-600 text-[12.5px] font-semibold">The total can't be below R0 — check the discount lines.</p>
        </div>

        <div class="card p-4 sm:p-6 mb-6 max-w-md">
            <label class="block text-label text-[11px] uppercase tracking-wider mb-1.5 font-semibold" for="deposit">Deposit required (R) <span class="normal-case font-normal text-label">(optional)</span></label>
            @if ($depositLocked)
                {{-- Received money stays as recorded; it's sent along so the totals check still works. --}}
                <input type="hidden" name="deposit_amount" value="{{ $booking->deposit_amount }}">
                <p class="text-ink text-[14px] font-semibold">R{{ number_format((float) $booking->deposit_amount, 2) }}</p>
                <p class="text-emerald-700 text-[12px] mt-0.5">Received {{ $booking->deposit_paid_at->format('d M Y') }} — can't be changed. The total can't go below it.</p>
            @else
                <input id="deposit" type="number" name="deposit_amount" x-model="deposit" step="0.01" min="0" inputmode="decimal" placeholder="Leave blank for no deposit"
                       class="w-full bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
            @endif
            <p x-show="deposit !== '' && parseFloat(deposit) > total" x-cloak class="text-rose-600 text-[12px] font-semibold mt-1.5">The deposit is more than the {{ $docWord }} total.</p>
        </div>

        <div class="flex items-center justify-between gap-3 flex-wrap">
            <a href="{{ $backUrl }}" class="text-muted text-[13px] font-semibold hover:text-ink transition-colors">Cancel</a>
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ $isInvoice ? route('admin.invoices.pdf.preview', $booking) : route('admin.bookings.quote-pdf.preview', $booking) }}" target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-2 bg-white border border-line text-ink font-semibold px-4 py-2.5 rounded-xl text-[13px] hover:border-navy transition-colors">
                    Preview saved PDF
                </a>
                <button type="submit" class="btn-primary" :disabled="total < 0">{{ $isInvoice ? 'Save revised invoice' : 'Save quote' }} &middot; <span x-text="money(total)"></span></button>
            </div>
        </div>
    </form>

    @if ($isCustom)
        <form method="POST" action="{{ route('admin.bookings.quote.reset', $booking) }}" class="mt-8 pt-6 border-t border-line" x-data="{ sure: false }">
            @csrf
            @method('DELETE')
            <p class="text-muted text-[12.5px] mb-2">Changed your mind? Go back to the prices worked out from the service and add-ons. Your edited lines are thrown away.</p>
            <button type="button" x-show="!sure" @click="sure = true" class="text-rose-500 text-[13px] font-semibold hover:opacity-70 transition-opacity">Reset to automatic pricing</button>
            <div x-show="sure" x-cloak class="flex items-center gap-3">
                <button type="submit" class="px-4 py-2 rounded-xl text-[13px] font-semibold bg-rose-500 text-white hover:bg-rose-600 transition-colors">Yes, reset the quote</button>
                <button type="button" @click="sure = false" class="text-muted text-[13px] font-semibold hover:text-ink">Keep my edits</button>
            </div>
        </form>
    @endif

@endsection
