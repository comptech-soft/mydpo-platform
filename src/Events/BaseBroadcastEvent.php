<?php

namespace MyDpo\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use MyDpo\Models\Customer;
use MyDpo\Models\CustomerNotification;
use MyDpo\Models\TemplateNotification;

class BaseBroadcastEvent implements ShouldBroadcast {

    public $input = NULL;

    public $entity = NULL;
    public $action = NULL;

    public $sender = NULL;
    public $notification_template = NULL;
    public $customer = NULL;
    public $receiver = NULL;

    public $notification_record = [];

    public function __construct($entity, $action, $input) {

        $this->entity = $entity;
        $this->action = $action;
        $this->input = $input;
        
        $this->sender = \Auth::user();
        
        if( ! $this->sender )
        {
            throw new \Exception('Nu avem user logat pentru acțiunea ' . $this->entity . '-' . $this->action . ' pe platforma ' . config('app.platform'));
        }

        $this->receiver = array_key_exists('receiver', $input) ? $input['receiver'] : NULL;

        if( array_key_exists('customer', $this->input))
        {
            $this->customer = $this->input['customer'];
        }
        else
        {
            if( array_key_exists('customer_id', $this->input))
            {
                $this->customer = Customer::find($this->input['customer_id']);
            }
        }
        
        $this->notification_template = TemplateNotification::findByEntityActionPlatform($entity, $action, config('app.platform'));

        if( ! $this->notification_template )
        {
            throw new \Exception('Nu avem notificare definită pentru acțiunea ' . $this->entity . '-' . $this->action . ' pe platforma ' . config('app.platform'));
        }

        $this->notification_record = [

            'type_id' => $this->notification_template->id,
            'subject_type' => NULL,
            'subject_id' => NULL,
            'sender_id' => $this->sender->id,
            'customer_id' => $this->customer ? $this->customer->id : NULL,
            'receiver_id'=> $this->receiver ? $this->receiver->id : NULL,
            'event' => $this->entity . '-' . $this->action,
            'status' => 'sended',
            'date_from' => NULL,
            'date_to' => NULL,
            'readed_at' => NULL,
            'message' => $this->notification_template->message,
            'props' => [
                'title' => $this->notification_template->title,
            ],
            'deleted' => 0,
            'created_by' => $this->sender->id,
        ];

        dd($this->notification_record);

    }

    public function broadcastAs() {
        return $this->entity . '-' . $this->action;
    }

    public function broadcastOn() {
        return new PrivateChannel($this->entity . '-' . $this->action);
    }

    public function broadcastWith() {
        return $this->notification_record;
    }

    public function InsertNotification() {
        CustomerNotification::create($this->notification_record);
    }

    public function CreateMessage($replacements) {
        $r = $this->notification_template->message;
        foreach($replacements as $key => $value)
        {
            $r = \Str::replace('[' . $key . ']', $value, $r);
        }
        $this->notification_record['message'] = $r;
    }

    public function SetSubject($subject_type, $subject_id) {
        $this->notification_record['subject_type'] = $subject_type;
        $this->notification_record['subject_id'] = $subject_id;
    }

}