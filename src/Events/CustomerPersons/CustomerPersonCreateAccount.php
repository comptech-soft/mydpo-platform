<?php

namespace MyDpo\Events\CustomerPersons;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use MyDpo\Events\BaseBroadcastEvent;
use MyDpo\Mail\CustomerPersons\CustomerPersonCreateAccount as CreateAccountMail;

class CustomerPersonCreateAccount extends BaseBroadcastEvent {

    public $account = NULL;
    public $roleUser = NULL;


    public function __construct($input) {

        parent::__construct('persons', 'create', $input);
               
        $this->account = $this->input['account'];
        $this->roleUser = $this->input['roleUser'];

        $this->SetSubject(__CLASS__, $this->account->id);

        $this->CreateMessage([
            // 'nume-fisier' => $this->file->file_original_name,
            // 'nume-folder' => $this->folder->name,
        ]);

        $this->InsertNotification();

        dd(__METHOD__, $input);

        \Mail::to($this->account->user)->send(new CreateAccountMail($this->entity, $this->action, $this->input));
    }

    
}
