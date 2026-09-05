<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegistrationActivation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public string $firstName, public string $lastName, public string $activationUrl)
    {
        //
    }

    /**
     * Get the message envelope. Matches old's real subject exactly - the
     * old code's Mail::send() closure hardcodes "Syndicate" here, not the
     * lang-key "Email Activation" value used only in the blade's <title>.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Syndicate',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.registration-activation',
            with: [
                'firstName' => $this->firstName,
                'lastName' => $this->lastName,
                'activationUrl' => $this->activationUrl,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
