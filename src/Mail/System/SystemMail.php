<?php

namespace MyDpo\Mail\System;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SystemMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct($user) {
        
        $this->email = $user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope() {
        return new Envelope(
            subject: 'Sample Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content() {
        return new Content(
            markdown: 'aaa.bb.cc',
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
