<?php

namespace MyDpo\Performers\User;

use MyDpo\Helpers\Perform;
use MyDpo\Models\User;

class UpdateStatus extends Perform {

    public function Action() {
      
        
        $user = User::find($this->input['id']);

        if($this->input['status'] == 1)
        {
            $user->Deactivate();
        }
        else
        {
            $user->Activate();
        }

    }

} 