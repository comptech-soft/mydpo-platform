<?php

namespace MyDpo\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;
use MyDpo\Models\TemplateEmail;

class BaseEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $input = NULL;

    public $entity = NULL;
    public $action = NULL;

    public $email_template = NULL;
    public $actionUrl = NULL;

    public function __construct($entity, $action, $input) {

        $this->entity = $entity;
        $this->action = $action;
        $this->input = $input;

        $this->email_template = TemplateEmail::findByEntityActionPlatform($entity, $action, config('app.platform'));
        
        if( ! $this->email_template )
        {
            throw new \Exception('Nu avem email definit pentru acțiunea ' . $this->entity . '-' . $this->action . ' pe platforma ' . config('app.platform'));
        }
    }

    /**
     * Get the message envelope.
     */
    public function envelope() {
        return new Envelope(
            from: new Address(config('mail.from.address'),  config('mail.from.name')),
            subject:  $this->email_template->subject,
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
                'introLines' => [
                    $this->email_template->body,
                ],
                'outroLines' => [],
                'actionUrl' => $this->actionUrl,
                'actionText' => $this->email_template->props['actionText'],
                'displayableActionUrl' => $this->actionUrl,
                'salutation' => 'O zi frumoasă!',
                'team' => 'Echipa decalex!',
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
