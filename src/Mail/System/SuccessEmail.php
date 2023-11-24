<?php

namespace MyDpo\Mail\System;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class SuccessEmail extends Mailable {

    use Queueable, SerializesModels;

    public $user = NULL;
    public $sender = NULL;
    public $template = NULL;
    public $payload = NULL;

    public function __construct($user, $sender, $template, $payload) {

        $this->user = $user;
        $this->sender = $sender;
        $this->template = $template;
        $this->payload = $payload;

    }

    /**
     * Get the message envelope.
     */
    public function envelope() {
        return new Envelope(
            from: new Address($this->sender->email,  $this->sender->full_name),
            subject: 'Email trimis cu succes',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content() {
        
        $markdown = 'emails.system';
        
        return new Content(
            markdown: $markdown,
            with: [
                'user' => collect($this->user->toArray())->only(['id', 'email', 'first_name', 'last_name'])->toArray(),
                'body' => $this->BodyContent($input),
                ...$input,
            ],
        );
    }

    protected function BodyContent($input) {


        $body = 'Email trimis cu succes.';

        return $body;
    }
    
    /**
     * Get the attachments for the message.
     */
    public function attachments() {
        return [];
    }
}
