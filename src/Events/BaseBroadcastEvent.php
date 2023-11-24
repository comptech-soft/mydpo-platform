<?php

namespace MyDpo\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
// use MyDpo\Models\Customer\Customer;
// use MyDpo\Models\CustomerNotification;
use MyDpo\Models\Livrabile\Notifications\TemplateNotification;
use MyDpo\Models\Livrabile\Emails\TemplateEmail;

class BaseBroadcastEvent implements ShouldBroadcast {

    public $input = NULL;
    public $template_name = NULL;

    public $template_email = NULL;
    public $template_notification = NULL;

    public function __construct($template_name, $input) {

        $this->template_name = $template_name;
        $this->input = $input;
        
        $this->template_email = TemplateEmail::FindByName($template_name);
        
        if(!! $this->template_email)
        {
            TemplateEmail::doSend(['customers' => $this->customers, 'payload' => $this->input], $this->template_email);
        }

        $this->template_notification = TemplateNotification::FindByName($template_name);

        if(!! $this->template_notification)
        {
            TemplateNotification::doSend(['customers' => $this->customers, 'payload' => $this->input], $this->template_notification);
        }
    }

    public function broadcastAs() {
        if(!! $this->template_notification)
        {
            return $this->template_name;
        }
        return NULL;
    }

    public function broadcastOn() {
        if(!! $this->template_notification)
        {
            return new PrivateChannel($this->template_name);
        }
    }

    public function broadcastWith() {
        if(!! $this->template_email)
        {
            return $this->template_email->toArray();
        }
        return NULL;
    }

    // public function InsertNotification() {
    //     CustomerNotification::create($this->notification_record);
    // }

    // public function CreateMessage($replacements) {
    //     $r = $this->notification_template->message;
    //     foreach($replacements as $key => $value)
    //     {
    //         $r = \Str::replace('[' . $key . ']', $value, $r);
    //     }
    //     $this->notification_record['message'] = $r;
    // }

    // public function SetSubject($subject_type, $subject_id) {
    //     $this->notification_record['subject_type'] = $subject_type;
    //     $this->notification_record['subject_id'] = $subject_id;
    // }

    public function __get($property) {
        return array_key_exists($property, $this->input) ? $this->input[$property] : NULL;
    }

}