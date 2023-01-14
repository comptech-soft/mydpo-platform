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

        $this->SetSubject(__CLASS__, $this->file->id);

        $this->CreateMessage([
            // 'nume-fisier' => $this->file->file_original_name,
            // 'nume-folder' => $this->folder->name,
        ]);

        $this->InsertNotification();

    }
    
}
