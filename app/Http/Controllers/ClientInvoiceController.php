<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class ClientInvoiceController extends Controller
{
    public function show(Booking $booking): View
    {
        abort_if(blank($booking->accepted_token) || blank($booking->invoice_number), 404);

        return view('client-invoice.show', ['booking' => $booking]);
    }

    public function download(Booking $booking): Response
    {
        abort_if(blank($booking->accepted_token) || blank($booking->invoice_number), 404);

        $booking->loadMissing('client');

        return Pdf::loadView('pdf.invoice', ['booking' => $booking])
            ->setPaper('a4')
            ->download("SpringKleaners-{$booking->invoice_number}.pdf");
    }
}
