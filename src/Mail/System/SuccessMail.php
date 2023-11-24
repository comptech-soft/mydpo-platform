<?php

namespace MyDpo\Mail\System;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class SuccessMail extends Mailable {

    use Queueable, SerializesModels;

    public $user = NULL;
    public $sender = NULL;
    public $template = NULL;
    public $payload = NULL;

    public function __construct($user, $sender, $template, $payload ) {
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
        return new Content(
            markdown: 'emails.system',
            with: [
                'user' => collect($this->user->toArray())->only(['id', 'email', 'first_name', 'last_name'])->toArray(),
                'body' => $this->BodyContent(),
            ],
        );
    }

    protected function BodyContent() {
        $body = [
            'Email trimis cu succes.',
            $this->template['name'],
            $this->template['subject'],
            $this->user->full_name,
        ];
        return implode('<br/>', $body);
    }
    
    /**
     * Get the attachments for the message.
     */
    public function attachments() {
        return [];
    }
}
