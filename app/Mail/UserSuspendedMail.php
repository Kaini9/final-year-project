<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserSuspendedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $reason;
    public $suspendedUntil;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $reason, $suspendedUntil)
    {
        $this->user = $user;
        $this->reason = $reason;
        $this->suspendedUntil = $suspendedUntil;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Notice: Your FashionConnect Account has been Suspended',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            markdown: 'emails.user_suspended',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
