<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $inviteeName,
        public string $inviterName,
        public string $role,
        public string $inviteUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: "You've been invited to SpringKleaners Admin");
    }

    public function content(): Content
    {
        return new Content(view: 'emails.admin.user-invitation');
    }
}
