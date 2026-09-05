<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Ports the old CMS's Api\JoinUsController mail send exactly: subject
 * "Syndicate" (matches the pattern used by RegistrationActivation/
 * PasswordResetCode), fixed body text, and the same PDF attachment.
 *
 */
class JoinUsMessage extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public string $name)
    {
        //
    }

    /**
     * Get the message envelope.
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
            view: 'emails.join-us',
            with: [
                'name' => $this->name,
                // Named `body`, not `message` - Laravel's Mailable already
                // injects a `$message` variable into every mail view (the
                // underlying Message instance), so reusing that name here
                // silently shadows a plain string with that object instead
                // (a real bug caught by sending a real test email).
                'body' => 'Fill the attached form, scan it and resend it back to info@mobilesyndicate.net',
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
        return [
            Attachment::fromPath(public_path('documents/syndicate_join_form.pdf')),
        ];
    }
}
