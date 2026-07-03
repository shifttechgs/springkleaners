<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\Response;

class InvoicePdfController extends Controller
{
    public function download(Booking $booking): Response
    {
        abort_if(blank($booking->invoice_number), 404);

        return $this->build($booking)->download("SpringKleaners-{$booking->invoice_number}.pdf");
    }

    public function preview(Booking $booking): Response
    {
        abort_if(blank($booking->invoice_number), 404);

        return $this->build($booking)->stream("SpringKleaners-{$booking->invoice_number}.pdf");
    }

    private function build(Booking $booking)
    {
        $booking->loadMissing('client');

        return Pdf::loadView('pdf.invoice', ['booking' => $booking])->setPaper('a4');
    }
}
