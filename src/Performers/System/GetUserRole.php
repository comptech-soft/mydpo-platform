<?php

namespace MyDpo\Performers\System;

use MyDpo\Helpers\Perform;
use MyDpo\Models\Activation;

class GetUserRole extends Perform {

    public function Action() {
    
        $user = \Auth::user();

        $activation = Activation::byUserAndCustomer($user->id, $this->input['customer_id']);

        if( ! $activation || ($activation->activated == 0))
        {
            return NULL;
        }

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