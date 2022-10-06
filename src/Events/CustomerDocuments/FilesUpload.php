<?php

namespace MyDpo\Events\CustomerDocuments;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use MyDpo\Models\Customer;

class FilesUpload implements ShouldBroadcast {
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $input = NULL;

    public $sender = NULL;
    public $customr = NULL;
    public $file = NULL;

    public function __construct($input) {
        $this->input = $input;
        
        $this->sender = \Auth::user();
        $this->customer = Customer::find($this->input['customer_id']);
        $this->file = $this->input['file'];
        
        dd($this->sender->full_name, $this->customer->name, $this->file->file_original_name);
    }

    public function broadcastAs() {
        return 'files-upload';
    }

    public function broadcastOn() {
        return new PrivateChannel('files-upload');
    }
}
