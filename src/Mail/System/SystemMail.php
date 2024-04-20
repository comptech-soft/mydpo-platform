<?php

namespace MyDpo\Mail\System;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class SystemMail extends Mailable {

    use Queueable, SerializesModels;

    public $user = NULL;
    public $sender = NULL;
    public $template = NULL;
    public $payload = NULL;

    public function __construct($user, $sender, $template, $payload) {


        dd($user, $sender, $template, $payload);

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
            subject: $this->template['subject'],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content() {
        
        $markdown = \View::exists($markdown = ('emails.' . $this->template['name'])) ? $markdown : 'emails.system';
        
        if(!!$this->template['before_method'])
        {
            list($className, $methodName) = explode("::", $this->template['before_method']);

            $input = [];

            if (method_exists($className, $methodName)) 
            {
                $input = $className::$methodName($this->user, $this->sender, $this->template, $this->payload);
            } 
        }

        
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

        $body = $this->template['body'];

        foreach(['email', 'first_name', 'last_name', 'full_name'] as $i => $field)
        {
            $body = \Str::replace('[' . $field . ']', $this->user->{$field}, $body);
        }

        foreach($input as $field => $value)
        {
            $body = \Str::replace('[' . $field . ']', $value, $body);
        }
        
        return $body;
    }
    
    /**
     * Get the attachments for the message.
     */
    public function attachments() {
        return [];
    }
}
