<?php

namespace MyDpo\Performers\System;

use MyDpo\Helpers\Perform;

class GetUserRole extends Perform {

    public function Action() {
    
        $user = \Auth::user();

        $r = NULL;
        foreach($user->roles as $i => $role) 
        {
            if($role->pivot->customer_id && ($role->pivot->customer_id == $this->input['customer_id']))
            {
                $r = $role;
            }
        }

        $this->payload = $r;
    }
    
} 