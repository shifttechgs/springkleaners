{{-- Phones/tablets: the booking as an app screen — who, how much, the one next step, then settings-style rows.
     Price/deposit and status/notes are edited in their own sheets, matching the two server actions behind them:
     send-quote (price + deposit, issues the client link, sets Quoted) and update (status, price, notes). --}}
@php
    $serviceName = \App\Support\Services::find($booking->service)['name'] ?? $booking->service;
    $price = (float) ($booking->quoted_price ?? $booking->total ?? 0);
    $status = $booking->status;
    $S = \App\Enums\BookingStatus::class;
    $place = trim(collect([$booking->address, $booking->suburb])->filter()->implode(', '));
    $quoteUrl = $booking->accepted_token ? route('quote.show', $booking->accepted_token) : null;
    $quoteMessage = "Hi {$booking->name}! Here's your SpringKleaners quote for {$serviceName}: R".number_format($price, 2)."\n\nView & confirm here: {$quoteUrl}";
    $confirmMessage = "Hi {$booking->name}! Your SpringKleaners booking is confirmed for ".$booking->date->format('d M Y')." at {$booking->time}.".($booking->deposit_amount > 0 && ! $booking->deposit_paid_at ? "\n\nDeposit due: R".number_format((float) $booking->deposit_amount, 2)." — ".\App\Support\Company::bankName().", Branch Code ".\App\Support\Company::branchCode().", Account No ".\App\Support\Company::accountNo().". ".\App\Support\Company::referenceNote() : "")."\n\nSee you then!";
    $reviewMessage = "Thank you for choosing SpringKleaners, {$booking->name}! We'd love a quick review: ".\App\Support\Company::reviewUrl();
    $chevron = '<svg class="ios-chevron" viewBox="0 0 8 14" aria-hidden="true"><path d="M1 1l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
    $waIcon = '<svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347M12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0012.05 0"/></svg>';
@endphp

<div class="lg:hidden" x-data="{ sheet: null, copied: false }"
     x-effect="document.body.classList.toggle('overflow-hidden', sheet !== null)"
     x-on:keydown.escape.window="sheet = null">

    {{-- Who --}}
    <div class="flex items-center gap-3 mb-4">
        <div class="avatar" style="width:52px;height:52px;border-radius:999px;font-size:20px;">{{ strtoupper(substr($booking->name, 0, 1)) }}</div>
        <div class="min-w-0">
            <p class="text-[24px] font-bold tracking-tight leading-tight text-ink truncate">{{ $booking->name }}</p>
            <p class="text-[15px] text-muted truncate">{{ $serviceName }}</p>
        </div>
    </div>

    {{-- Contact actions, Contacts-app style --}}
    <div class="grid grid-cols-4 gap-2 mb-5">
        <a href="tel:{{ preg_replace('/[^\d+]/', '', $booking->phone) }}" class="ios-action-tile">
            <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
            Call
        </a>
        <a href="https://wa.me/{{ $waNumber }}" target="_blank" rel="noopener noreferrer" class="ios-action-tile">
            {!! $waIcon !!}
            WhatsApp
        </a>
        @if ($place !== '')
            <a href="https://maps.apple.com/?q={{ urlencode($place) }}" target="_blank" rel="noopener noreferrer" class="ios-action-tile">
                <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                Directions
            </a>
        @else
            <span class="ios-action-tile opacity-40" aria-disabled="true">
                <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                Directions
            </span>
        @endif
        @if ($booking->client)
            <a href="{{ route('admin.clients.show', $booking->client) }}" class="ios-action-tile">
                <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                History
            </a>
        @else
            <a href="{{ route('admin.bookings.quote-pdf.preview', $booking) }}" target="_blank" rel="noopener noreferrer" class="ios-action-tile">
                <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                Quote PDF
            </a>
        @endif
    </div>

    {{-- How much, when, and the one next step --}}
    <div class="card p-4 mb-6">
        <div class="flex items-start justify-between gap-3">
            <div class="min-w-0">
                <p class="text-[13px] text-label">{{ $booking->quoted_price ? 'Quoted price' : 'Estimate' }}</p>
                <p class="text-[34px] font-bold tracking-tight leading-none mt-1 text-ink">R{{ number_format($price, 2) }}</p>
            </div>
            <x-admin.status-badge :status="$status" />
        </div>
        <p class="text-[15px] text-muted mt-3">{{ $booking->date->format('D, d M Y') }} &middot; {{ $booking->time }}@if ($booking->suburb) &middot; {{ $booking->suburb }}@endif</p>
        @if ($booking->deposit_amount > 0)
            <p class="text-[15px] mt-1 {{ $booking->deposit_paid_at ? 'text-emerald-600' : 'text-amber-700' }}">
                Deposit R{{ number_format((float) $booking->deposit_amount, 2) }} &middot; {{ $booking->deposit_paid_at ? 'received '.$booking->deposit_paid_at->format('d M') : 'not yet received' }}
            </p>
        @endif

        <div class="mt-4 space-y-2">
            @if (! $quoteUrl)
                <form method="POST" action="{{ route('admin.bookings.send-quote', $booking) }}">
                    @csrf
                    <input type="hidden" name="quoted_price" value="{{ $price }}">
                    <input type="hidden" name="deposit_amount" value="{{ $booking->deposit_amount }}">
                    <button type="submit" class="btn-gold w-full">Send Quote &middot; R{{ number_format($price, 2) }}</button>
                </form>
                <button type="button" class="ios-secondary-btn" x-on:click="sheet = 'price'">Adjust price first</button>
            @elseif ($status === $S::Accepted)
                <form method="POST" action="{{ route('admin.bookings.update', $booking) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="{{ $S::Completed->value }}">
                    <input type="hidden" name="quoted_price" value="{{ $booking->quoted_price }}">
                    <input type="hidden" name="admin_notes" value="{{ $booking->admin_notes }}">
                    <button type="submit" class="btn-primary w-full">Mark as Completed</button>
                </form>
                <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode($confirmMessage) }}" target="_blank" rel="noopener noreferrer" class="ios-whatsapp-btn">
                    {!! $waIcon !!} Send booking confirmation
                </a>
            @elseif ($status === $S::Completed)
                <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode($reviewMessage) }}" target="_blank" rel="noopener noreferrer" class="ios-whatsapp-btn">
                    {!! $waIcon !!} Ask for a review
                </a>
                @if ($booking->invoice_number)
                    <a href="{{ route('admin.invoices.show', $booking) }}" class="ios-secondary-btn">View invoice {{ $booking->invoice_number }}</a>
                @endif
            @else
                <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode($quoteMessage) }}" target="_blank" rel="noopener noreferrer" class="ios-whatsapp-btn">
                    {!! $waIcon !!} Send quote on WhatsApp
                </a>
                <button type="button" class="ios-secondary-btn" x-on:click="navigator.clipboard.writeText('{{ $quoteUrl }}'); copied = true; setTimeout(() => copied = false, 2000)">
                    <span x-text="copied ? 'Link copied' : 'Copy quote link'"></span>
                </button>
            @endif
        </div>
    </div>

    {{-- Quote settings --}}
    <p class="ios-section-label">Quote</p>
    <div class="ios-group mb-6">
        <button type="button" class="ios-cell w-full text-left" x-on:click="sheet = 'price'">
            <span class="flex-1">Price</span>
            <span class="ios-cell-value">R{{ number_format($price, 2) }}</span>
            {!! $chevron !!}
        </button>
        <button type="button" class="ios-cell w-full text-left" x-on:click="sheet = 'price'">
            <span class="flex-1">Deposit</span>
            <span class="ios-cell-value">{{ $booking->deposit_amount > 0 ? 'R'.number_format((float) $booking->deposit_amount, 2) : 'None' }}</span>
            {!! $chevron !!}
        </button>
        <button type="button" class="ios-cell w-full text-left" x-on:click="sheet = 'status'">
            <span class="flex-1">Status</span>
            <span class="ios-cell-value">{{ $status->label() }}</span>
            {!! $chevron !!}
        </button>
        <button type="button" class="ios-cell w-full text-left" x-on:click="sheet = 'status'">
            <span class="flex-shrink-0">Notes</span>
            <span class="ios-cell-value flex-1 truncate text-right">{{ $booking->admin_notes ? \Illuminate\Support\Str::limit($booking->admin_notes, 40) : 'Add' }}</span>
            {!! $chevron !!}
        </button>
    </div>

    {{-- Documents and follow-ups --}}
    <p class="ios-section-label">Documents</p>
    <div class="ios-group mb-6">
        <a href="{{ route('admin.bookings.quote-pdf.preview', $booking) }}" target="_blank" rel="noopener noreferrer" class="ios-cell">
            <span class="flex-1">Preview quote PDF</span>{!! $chevron !!}
        </a>
        <a href="{{ route('admin.bookings.quote-pdf', $booking) }}" class="ios-cell">
            <span class="flex-1">Download quote PDF</span>{!! $chevron !!}
        </a>
        @if ($booking->invoice_number)
            <a href="{{ route('admin.invoices.show', $booking) }}" class="ios-cell">
                <span class="flex-1">Invoice</span><span class="ios-cell-value">{{ $booking->invoice_number }}</span>{!! $chevron !!}
            </a>
        @endif
        @if ($quoteUrl && $status !== $S::Quoted && $status !== $S::Pending)
            <button type="button" class="ios-cell w-full text-left" x-on:click="navigator.clipboard.writeText('{{ $quoteUrl }}'); copied = true; setTimeout(() => copied = false, 2000)">
                <span class="flex-1" x-text="copied ? 'Link copied' : 'Copy quote link'"></span>
            </button>
        @endif
        <a href="{{ route('admin.expenses.create', ['booking_id' => $booking->id]) }}" class="ios-cell">
            <span class="flex-1">Add fuel or other expense</span>{!! $chevron !!}
        </a>
    </div>

    @if (($booking->deposit_amount > 0 && ! $booking->deposit_paid_at) || $status === $S::Accepted || $status === $S::Completed)
        <p class="ios-section-label">Follow-up</p>
        <div class="ios-group mb-6">
            @if ($booking->deposit_amount > 0 && ! $booking->deposit_paid_at)
                <form method="POST" action="{{ route('admin.invoices.mark-deposit-paid', $booking) }}">
                    @csrf
                    <button type="submit" class="ios-cell w-full text-left"><span class="flex-1 ios-tint">Mark deposit as received</span></button>
                </form>
            @endif
            @if ($status === $S::Accepted)
                <form method="POST" action="{{ route('admin.bookings.send-confirmation-email', $booking) }}">
                    @csrf
                    <button type="submit" class="ios-cell w-full text-left">
                        <span class="flex-1 ios-tint">Email booking confirmation</span>
                        @if ($booking->confirmation_sent_at)<span class="ios-cell-value">Sent {{ $booking->confirmation_sent_at->format('d M') }}</span>@endif
                    </button>
                </form>
            @endif
            @if ($status === $S::Completed)
                <form method="POST" action="{{ route('admin.bookings.send-thank-you-email', $booking) }}">
                    @csrf
                    <button type="submit" class="ios-cell w-full text-left">
                        <span class="flex-1 ios-tint">Email thank-you &amp; review link</span>
                        @if ($booking->thank_you_sent_at)<span class="ios-cell-value">Sent {{ $booking->thank_you_sent_at->format('d M') }}</span>@endif
                    </button>
                </form>
            @endif
        </div>
    @endif

    <p class="ios-section-label">Job details</p>

    {{-- Sheet: price & deposit (send-quote) --}}
    <div x-show="sheet === 'price'" x-cloak class="fixed inset-0 z-50" role="dialog" aria-modal="true" aria-label="Quote price">
        <div x-show="sheet === 'price'" x-transition.opacity.duration.250ms class="absolute inset-0 bg-black/30" x-on:click="sheet = null"></div>
        <form method="POST" action="{{ route('admin.bookings.send-quote', $booking) }}"
              x-show="sheet === 'price'"
              x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
              x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full"
              class="ios-sheet absolute inset-x-0 bottom-0">
            @csrf
            <div class="mx-auto mt-2 mb-1 h-[5px] w-9 rounded-full bg-[#c7c9d1]" aria-hidden="true"></div>
            <div class="grid grid-cols-[1fr_auto_1fr] items-center px-4 pt-1 pb-4">
                <button type="button" class="ios-text-btn justify-self-start" x-on:click="sheet = null">Cancel</button>
                <p class="text-[17px] font-semibold text-ink">Quote Price</p>
                <span></span>
            </div>
            <div class="ios-group mx-4">
                <label class="ios-cell">
                    <span class="flex-shrink-0">Price (R)</span>
                    <input type="number" name="quoted_price" step="0.01" min="0" inputmode="decimal" required value="{{ $booking->quoted_price ?? $booking->total }}" class="ios-field" aria-label="Price in rand">
                </label>
                <label class="ios-cell">
                    <span class="flex-shrink-0">Deposit (R)</span>
                    <input type="number" name="deposit_amount" step="0.01" min="0" inputmode="decimal" value="{{ $booking->deposit_amount }}" placeholder="None" class="ios-field" aria-label="Deposit in rand">
                </label>
            </div>
            <p class="px-8 pt-2 pb-5 text-[13px] text-label leading-snug">
                Saving sends the client a quote link at this price.
                @if ($status === $S::Accepted || $status === $S::Completed)
                    This booking is {{ strtolower($status->label()) }} — saving moves it back to Quoted until the client accepts again.
                @endif
            </p>
            <div class="px-4">
                <button type="submit" class="btn-gold w-full">{{ $quoteUrl ? 'Update Quote' : 'Save & Send Quote' }}</button>
            </div>
        </form>
    </div>

    {{-- Sheet: status & notes (update) --}}
    <div x-show="sheet === 'status'" x-cloak class="fixed inset-0 z-50" role="dialog" aria-modal="true" aria-label="Status and notes">
        <div x-show="sheet === 'status'" x-transition.opacity.duration.250ms class="absolute inset-0 bg-black/30" x-on:click="sheet = null"></div>
        <form method="POST" action="{{ route('admin.bookings.update', $booking) }}" x-data="{ status: '{{ $status->value }}' }"
              x-show="sheet === 'status'"
              x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
              x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full"
              class="ios-sheet absolute inset-x-0 bottom-0">
            @csrf
            @method('PATCH')
            <input type="hidden" name="quoted_price" value="{{ $booking->quoted_price }}">
            <div class="mx-auto mt-2 mb-1 h-[5px] w-9 rounded-full bg-[#c7c9d1]" aria-hidden="true"></div>
            <div class="grid grid-cols-[1fr_auto_1fr] items-center px-4 pt-1 pb-4">
                <button type="button" class="ios-text-btn justify-self-start" x-on:click="sheet = null">Cancel</button>
                <p class="text-[17px] font-semibold text-ink">Status &amp; Notes</p>
                <span></span>
            </div>
            <p class="ios-section-label mx-4">Status</p>
            <div class="ios-group mx-4 mb-5" role="radiogroup" aria-label="Status">
                @foreach ($statuses as $option)
                    <label class="ios-cell cursor-pointer">
                        <input type="radio" name="status" value="{{ $option->value }}" x-model="status" class="sr-only">
                        <span class="badge-dot {{ $option->dotColor() }}" style="width:8px;height:8px;"></span>
                        <span class="flex-1">{{ $option->label() }}</span>
                        <svg x-show="status === '{{ $option->value }}'" class="h-4 w-4 text-navy" fill="none" stroke="currentColor" stroke-width="2.6" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    </label>
                @endforeach
            </div>
            <p x-show="status === '{{ $S::Completed->value }}' && '{{ $status->value }}' !== '{{ $S::Completed->value }}'" x-cloak class="px-8 -mt-3 mb-4 text-[13px] text-label">Completing the job creates its invoice.</p>
            <p class="ios-section-label mx-4">Notes for the team</p>
            <div class="ios-group mx-4 mb-5">
                <textarea name="admin_notes" rows="3" class="ios-field ios-field-area" placeholder="e.g. Gate code, client preferences">{{ $booking->admin_notes }}</textarea>
            </div>
            <div class="px-4">
                <button type="submit" class="btn-primary w-full">Save</button>
            </div>
        </form>
    </div>
</div>
