<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContentDeletedAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $contentType; // 'Post' or 'Job Post'
    public $reason;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $contentType, $reason)
    {
        $this->user = $user;
        $this->contentType = $contentType;
        $this->reason = $reason;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: "Notice: Your {$this->contentType} has been deleted",
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
            markdown: 'emails.content_deleted_admin',
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
