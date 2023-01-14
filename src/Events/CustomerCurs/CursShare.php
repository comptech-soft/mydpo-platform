<?php

namespace MyDpo\Events\CustomerCurs;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use MyDpo\Events\BaseBroadcastEvent;

class CursShare extends BaseBroadcastEvent {

    public $curs = NULL;
    
    public function __construct($input) {

        parent::__construct('curs', 'share', $input);
               
        $this->curs = $this->input['curs'];

        $this->SetSubject(__CLASS__, $this->curs->id);

        $this->CreateMessage([
            'nume-curs' => $this->curs->name,
        ]);

        $this->InsertNotification();

    }
    
}
