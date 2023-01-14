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

    public $file = NULL;
    public $folder = NULL;

    public function __construct($input) {

        parent::__construct('curs', 'share', $input);
               
        // $this->file = $this->input['file'];
        // $this->folder = Folder::find($this->input['folder_id']);

        // $this->SetSubject(__CLASS__, $this->file->id);

        // $this->CreateMessage([
        //     'nume-fisier' => $this->file->file_original_name,
        //     'nume-folder' => $this->folder->name,
        // ]);

        // $this->InsertNotification();

    }
    
}
