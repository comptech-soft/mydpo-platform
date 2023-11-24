<?php

namespace MyDpo\Events\Customer\Livrabile\Documents;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use MyDpo\Events\BaseBroadcastEvent;

class CreateAccountActivation extends BaseBroadcastEvent {

    public function __construct($template, $input) {
        parent::__construct($template, $input);
    }

}