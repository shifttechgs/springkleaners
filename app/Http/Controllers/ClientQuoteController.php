<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ClientQuoteController extends Controller
{
    public function show(Booking $booking): View
    {
        abort_if(! $booking->accepted_token, 404);

        return view('client-quote.show', [
            'booking' => $booking,
        ]);
    }

    public function accept(Booking $booking): RedirectResponse
    {
        abort_if(! $booking->accepted_token, 404);

        if ($booking->status === BookingStatus::Quoted) {
            $booking->update([
                'status' => BookingStatus::Accepted,
                'accepted_at' => now(),
            ]);
        }

        return redirect()->route('quote.show', $booking->accepted_token)
            ->with('status', 'Thanks — your quote has been accepted!');
    }

    public function decline(Booking $booking): RedirectResponse
    {
        abort_if(! $booking->accepted_token, 404);

        if ($booking->status === BookingStatus::Quoted) {
            $booking->update([
                'status' => BookingStatus::Declined,
            ]);
        }

        return redirect()->route('quote.show', $booking->accepted_token)
            ->with('status', 'Quote declined.');
    }
}
