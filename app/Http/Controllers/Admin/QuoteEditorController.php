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

        if ($moved) {
            if ($newDate->lt(now()->startOfDay())) {
                throw ValidationException::withMessages(['date' => 'Pick today or a later date.']);
            }

            if (! Booking::isBookableDay($newDate)) {
                throw ValidationException::withMessages(['date' => 'We only take bookings on Saturdays and Sundays — pick a weekend date.']);
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

        if ($total < 0) {
            throw ValidationException::withMessages(['items' => 'The quote total can\'t be below R0 — check the discount lines.']);
        }

        if ($deposit !== null && $deposit > $total) {
            throw ValidationException::withMessages(['deposit_amount' => 'The deposit can\'t be more than the quote total (R'.number_format($total, 2).').']);
        }

        // Keep the scope wording the client was quoted on, before the price changes.
        ServiceSnapshot::ensureStored($booking);

        $changes = [
            'quote_items' => QuotePricing::storable($lines),
            'quoted_price' => $total,
            'deposit_amount' => $deposit ?: null,
            'quote_edited_at' => now(),
        ];

        $priceChanged = abs($total - (float) ($booking->quoted_price ?? $booking->total ?? 0)) >= 0.005;
        $message = 'Quote updated — total R'.number_format($total, 2).'.';

        // The client agreed to a different amount: they confirm the new price on the same link.
        if ($booking->status === BookingStatus::Accepted && $priceChanged) {
            $changes['status'] = BookingStatus::Quoted;
            $changes['accepted_at'] = null;
            $message .= ' The client had accepted the old price, so it is back to "Quoted" for them to confirm.';
        } elseif ($booking->accepted_token) {
            $message .= ' The client\'s quote link already shows the new version.';
        }

        if (! $moved) {
            $booking->update($changes);

            return redirect()->route('admin.bookings.show', $booking)->with('status', $message);
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

        return redirect()->route('admin.bookings.show', $booking)->with('status', $message);
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
            // A quote that hasn't gone out yet goes back to being an estimate; a sent one keeps a price.
            'quoted_price' => $booking->quote_sent_at ? $automatic : null,
            'quote_edited_at' => now(),
        ])->save();

        if ($booking->deposit_amount > $automatic) {
            $booking->update(['deposit_amount' => $automatic ?: null]);
        }

        return redirect()->route('admin.bookings.quote.edit', $booking)
            ->with('status', 'Back to automatic pricing — R'.number_format($automatic, 2).'.');
    }

    /** Once an invoice exists the quote is what was billed, so it can't change any more. */
    private function lockedRedirect(Booking $booking): ?RedirectResponse
    {
        if (blank($booking->invoice_number)) {
            return null;
        }

        return redirect()->route('admin.bookings.show', $booking)
            ->withErrors(['quote' => 'Invoice '.$booking->invoice_number.' has been issued for this job, so its quote is locked.']);
    }
}
