<?php

namespace MyDpo\Events\CustomerDocuments;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FilesUpload implements ShouldBroadcast {
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $input = NULL;
    public $sender = NULL;

    public function __construct($input) {
        $this->input = $input;
        $this->sender = \Auth::user()->id;

        dd($this->input, $this->sender);
    }

    public function broadcastAs() {
        return 'files-upload';
    }

    public function broadcastOn() {
        return new PrivateChannel('files-upload');
    }
}
