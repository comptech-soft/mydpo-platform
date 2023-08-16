<?php

namespace MyDpo\Performers\User;

use MyDpo\Helpers\Perform;
use MyDpo\Models\Authentication\User;

class UpdateStatus extends Perform {

    public function Action() {
      
        
        $user = User::find($this->input['id']);

        if(array_key_exists('status', $this->input) && $this->input['status'] == 1)
        {
            $user->Deactivate();
        }
        else
        {
            $user->Activate();
        }

        $this->payload['record'] = $user;

    }

} 