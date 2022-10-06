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

    public function __construct() {
        \Log::info(__METHOD__ . '--> Files Uploaded Events');
    }

    public function broadcastAs() {
        return 'files-upload';
    }

    public function broadcastOn() {
        return new PrivateChannel('files-upload');
    }
}
