<?php

namespace MyDpo\Mail\System;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class SystemMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user = NULL;
    public $sender = NULL;
    public $template = NULL;

    public function __construct($user, $sender, $template) {
        $this->user = $user;
        $this->sender = $sender;
        $this->template = $template;
    }

    /**
     * Get the message envelope.
     */
    public function envelope() {
        return new Envelope(
            from: new Address($this->sender->email,  $this->sender->full_name),
            subject: $this->template['subject'],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content() {
        return new Content(
            markdown: 'emails.system',
            with: [
                'user' => $this->user,
                'body' => $this->template['body'],
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments()
    {
        return [];
    }
}