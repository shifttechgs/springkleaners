<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\Response;

class QuotePdfController extends Controller
{
    public function download(Booking $booking): Response
    {
        return $this->build($booking)->download("SpringKleaners-Quote-{$booking->id}.pdf");
    }

    public function preview(Booking $booking): Response
    {
        return $this->build($booking)->stream("SpringKleaners-Quote-{$booking->id}.pdf");
    }

    private function build(Booking $booking)
    {
        $booking->loadMissing('client');

        return Pdf::loadView('pdf.quote', ['booking' => $booking])->setPaper('a4');
    }
}
