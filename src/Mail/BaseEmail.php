<?php

namespace MyDpo\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class BaseEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct() {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope() {
        return new Envelope(
            from: new Address('jeffrey@example.com', 'Jeffrey Way'),
            subject: 'Order Shipped',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content() {

        return new Content(
            // view: 'vendor.customer-persons.activate-account',
            markdown: 'vendor.notifications.email',
            with: [
                'level' => 'info',
                'introLines' => [],
                'outroLines' => [],
                'actionUrl' => config('app.url'),
                'actionText' => 'Bam vam...',
                'displayableActionUrl' => 'aaa',
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments() {
        return [];
    }
}
