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
use MyDpo\Models\TemplateNotification;

class BaseBroadcastEvent implements ShouldBroadcast {

    public $input = NULL;

    public $entity = NULL;
    public $action = NULL;

    public $sender = NULL;
    public $notification_template = NULL;
    public $customer = NULL;

    public function __construct($entity, $action, $input) {

        $this->entity = $entity;
        $this->action = $action;
        $this->input = $input;
        
        $this->sender = \Auth::user();

        if( array_key_exists('customer_id', $this->input))
        {
            $this->customer = Customer::find($this->input['customer_id']);
        }
        
        $this->notification_template = TemplateNotification::findByEntityActionPlatform($entity, $action, config('app.platform'));

        if( ! $this->notification_template )
        {
            throw new \Exception('NU avem notificare definită pentru acțiunea ' . $this->entity . '-' . $this->action . ' pe platforma ' . config('app.platform'));
        }
    }

    public function broadcastAs() {
        return $this->entity . '-' . $this->action;
    }

    public function broadcastOn() {
        return new PrivateChannel($this->entity . '-' . $this->action);
    }

}