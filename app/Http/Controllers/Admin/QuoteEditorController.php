<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BookingStatus;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Support\QuotePricing;
use App\Support\ServiceSnapshot;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Lets an admin change a generated quote line by line: reword, re-price, add extra work or a discount, remove
 * lines, set the deposit, and move the service date/slot. The saved lines become the quote everywhere the
 * client sees it (PDF, quote page, invoice), and the quoted price is always their sum.
 */
class QuoteEditorController extends Controller
{
    public const MAX_LINES = 30;

    public function edit(Booking $booking): View|RedirectResponse
    {
        if ($redirect = $this->lockedRedirect($booking)) {
            return $redirect;
        }

        return view('admin.bookings.quote-edit', [
            'booking' => $booking,
            'items' => QuotePricing::lineItems($booking),
            'isCustom' => QuotePricing::isCustom($booking),
        ]);
    }

    public function update(Request $request, Booking $booking): RedirectResponse
    {
        if ($redirect = $this->lockedRedirect($booking)) {
            return $redirect;
        }

        $data = $request->validate([
            'items' => 'required|array|min:1|max:'.self::MAX_LINES,
            'items.*.description' => 'required|string|max:255',
            'items.*.note' => 'nullable|string|max:500',
            'items.*.qty' => 'required|integer|min:1|max:999',
            'items.*.unit_price' => 'required|numeric|between:-1000000,1000000',
            'deposit_amount' => 'nullable|numeric|min:0',
            'date' => 'sometimes|required|date',
            'time' => ['sometimes', 'required', Rule::in(Booking::TIME_SLOTS)],
        ], [
            'items.required' => 'A quote needs at least one line.',
            'items.*.description.required' => 'Every line needs a description.',
            'items.*.qty.min' => 'Quantities start at 1. Use a negative price for a discount.',
            'time.in' => 'Pick one of the two slots: '.implode(' or ', Booking::TIME_SLOTS).'.',
        ]);

        // Rescheduling: only when the date or slot actually changes. Older bookings saved with a single time
        // ("9:00 AM") keep it as long as they stay in the same slot on the same day.
        $currentSlot = Booking::slotFor($booking->time);
        $newDate = isset($data['date']) ? Carbon::parse($data['date'])->startOfDay() : $booking->date->copy()->startOfDay();
        $newSlot = $data['time'] ?? $currentSlot;
        $moved = ! $newDate->isSameDay($booking->date) || $newSlot !== $currentSlot;

        if ($moved && ! $booking->isInvoiced()) {
            if ($newDate->lt(now()->startOfDay())) {
                throw ValidationException::withMessages(['date' => 'Pick today or a later date.']);
            }

            if (! Booking::isBookableDay($newDate)) {
                throw ValidationException::withMessages(['date' => Booking::unbookableDayMessage($newDate)]);
            }
        }

        $lines = array_map(fn (array $item) => QuotePricing::line(
            trim($item['description']),
            isset($item['note']) ? trim($item['note']) : null,
            (int) $item['qty'],
            (float) $item['unit_price'],
        ), array_values($data['items']));

        $total = QuotePricing::total($lines);
        $deposit = isset($data['deposit_amount']) ? round((float) $data['deposit_amount'], 2) : null;

        // A deposit that's been received is money in the bank: it stays as recorded.
        if ($booking->deposit_paid_at) {
            $deposit = (float) $booking->deposit_amount;
        }

        if ($total < 0) {
            throw ValidationException::withMessages(['items' => 'The quote total can\'t be below R0 — check the discount lines.']);
        }

        if ($deposit !== null && $deposit > $total) {
            throw ValidationException::withMessages(['deposit_amount' => $booking->deposit_paid_at
                ? 'A deposit of R'.number_format($deposit, 2).' has already been received, so the total can\'t be less than that.'
                : 'The deposit can\'t be more than the total (R'.number_format($total, 2).').']);
        }

        // Keep the scope wording the client was quoted on, before the price changes.
        ServiceSnapshot::ensureStored($booking);

        $changes = [
            'quote_items' => QuotePricing::storable($lines),
            'quoted_price' => $total,
            'deposit_amount' => $deposit ?: null,
            'quote_edited_at' => now(),
        ];

        if ($booking->isInvoiced()) {
            $changes['invoice_revised_at'] = now();
        }

        $priceChanged = abs($total - (float) ($booking->quoted_price ?? $booking->total ?? 0)) >= 0.005;
        $message = ($booking->isInvoiced() ? 'Invoice '.$booking->invoice_number.' revised' : 'Quote updated').' — total R'.number_format($total, 2).'.';

        // The client agreed to a different amount: they confirm the new price on the same link.
        if ($booking->status === BookingStatus::Accepted && $priceChanged) {
            $changes['status'] = BookingStatus::Quoted;
            $changes['accepted_at'] = null;
            $message .= ' The client had accepted the old price, so it is back to "Quoted" for them to confirm.';
        } elseif ($booking->isInvoiced()) {
            $message .= ' Send it to the client again so they have the corrected version.';
        } elseif ($booking->accepted_token) {
            $message .= ' The client\'s quote link already shows the new version.';
        }

        if (! $moved) {
            $booking->update($changes);

            return $this->afterSave($booking, $message);
        }

        // Moving to a new date/slot: check and save under the same per-date lock the website uses, so the
        // slot can't be taken by someone else between the check and the save.
        $changes['date'] = $newDate->toDateString();
        $changes['time'] = $newSlot;

        try {
            $clash = Booking::withDateLock($newDate, function () use ($booking, $newDate, $newSlot, $changes) {
                $taken = Booking::whereIn('status', BookingStatus::occupyingSlot())
                    ->whereDate('date', $newDate->toDateString())
                    ->whereKeyNot($booking->id)
                    ->pluck('time')
                    ->contains(fn ($time) => Booking::slotFor($time) === $newSlot);

                if ($taken) {
                    return true;
                }

                $booking->update($changes);

                return false;
            });
        } catch (LockTimeoutException) {
            $clash = true;
        }

        if ($clash) {
            throw ValidationException::withMessages(['time' => 'The '.$newSlot.' slot on '.$newDate->format('D j M Y').' is already booked — pick another slot or date.']);
        }

        $message .= ' Moved to '.$newDate->format('D j M Y').', '.$newSlot.'.';
        if ($booking->status === BookingStatus::Accepted) {
            $message .= ' Let the client know — you can resend the booking confirmation.';
        }

        return $this->afterSave($booking, $message);
    }

    /** Throw away the hand-edited lines and go back to the automatic service + add-on pricing. */
    public function reset(Booking $booking): RedirectResponse
    {
        if ($redirect = $this->lockedRedirect($booking)) {
            return $redirect;
        }

        $booking->quote_items = null;
        $automatic = QuotePricing::total(QuotePricing::automaticLineItems($booking));

        $booking->forceFill([
            // A quote that hasn't gone out yet goes back to being an estimate; a sent quote or an invoice keeps a price.
            'quoted_price' => ($booking->quote_sent_at || $booking->isInvoiced()) ? $automatic : null,
            'quote_edited_at' => now(),
            'invoice_revised_at' => $booking->isInvoiced() ? now() : $booking->invoice_revised_at,
        ])->save();

        if (! $booking->deposit_paid_at && $booking->deposit_amount > $automatic) {
            $booking->update(['deposit_amount' => $automatic ?: null]);
        }

        return redirect()->route('admin.bookings.quote.edit', $booking)
            ->with('status', 'Back to automatic pricing — R'.number_format($automatic, 2).'.');
    }

    /** Unpaid invoices can still be corrected (and are marked Revised). A paid invoice is a closed record. */
    private function lockedRedirect(Booking $booking): ?RedirectResponse
    {
        if (! $booking->isPriceLocked()) {
            return null;
        }

        return redirect()->route('admin.invoices.show', $booking)
            ->withErrors(['quote' => 'Invoice '.$booking->invoice_number.' has been paid, so it is locked.']);
    }

    /** Invoices go back to the invoice page (where it can be re-sent); quotes to the booking. */
    private function afterSave(Booking $booking, string $message): RedirectResponse
    {
        return redirect()->route($booking->isInvoiced() ? 'admin.invoices.show' : 'admin.bookings.show', $booking)
            ->with('status', $message);
    }
}
