<?php

namespace App\Mail;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DepositBackChecklistMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $name) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Your Free End-of-Tenancy Deposit-Back Checklist');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.lead-magnet.deposit-back-checklist', with: ['name' => $this->name]);
    }

    public function attachments(): array
    {
        $pdf = Pdf::loadView('pdf.deposit-back-checklist')->setPaper('a4');

        return [
            Attachment::fromData(fn () => $pdf->output(), 'SpringKleaners-Deposit-Back-Checklist.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
