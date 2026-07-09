<?php

namespace App\Http\Controllers;

use App\Mail\DepositBackChecklistMail;
use App\Models\LeadMagnetDownload;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;

class LeadMagnetController extends Controller
{
    public function depositBackChecklist(Request $request): Response
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        LeadMagnetDownload::create([
            'lead_magnet' => 'deposit-back-checklist',
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        Mail::to($data['email'])->send(new DepositBackChecklistMail($data['name']));

        return Pdf::loadView('pdf.deposit-back-checklist')
            ->setPaper('a4')
            ->download('SpringKleaners-Deposit-Back-Checklist.pdf');
    }
}
