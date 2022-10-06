<?php

namespace MyDpo\Events\CustomerDocuments;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use MyDpo\Events\BaseBroadcastEvent;

use MyDpo\Models\Folder;


class FilesUpload extends BaseBroadcastEvent {

    public $file = NULL;
    public $folder = NULL;


    public function __construct($input) {

        parent::__construct('files', 'upload', $input);

       
        $this->file = $this->input['file'];
        $this->folder = Folder::find($this->input['folder_id']);

        $this->SetSubject(__CLASS__, $this->file->id);

        dd('OK' , $this->notification_record);


    }

    
}
