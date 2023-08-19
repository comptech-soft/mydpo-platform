<?php

namespace MyDpo\Events\Notifications;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
// use MyDpo\Models\Customer\Customer;
// use MyDpo\Models\CustomerNotification;
// use MyDpo\Models\Livrabile\Notifications\TemplateNotification;

class NotificationEvent implements ShouldBroadcast {

    public $notification = NULL;

    // public $entity = NULL;
    // public $action = NULL;

    // public $sender = NULL;
    // public $notification_template = NULL;
    // public $customer = NULL;
    // public $receiver = NULL;

    // public $notification_record = [];

    public function __construct($notification) {

        $this->notification = $notification;
        
    }

    public function broadcastAs() {
        return 'notification';
    }

    public function broadcastOn() {
        return new PrivateChannel('notification');
    }

    public function broadcastWith() {
        return [
            'id' => $this->notification->id,
        ];
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

}