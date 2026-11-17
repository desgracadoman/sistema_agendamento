<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CompanyInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $companyName,
        public string $invitationUrl,
        public string $expiresAt
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Convite para cadastro empresarial',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.company-invitation',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
