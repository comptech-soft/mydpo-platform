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

        $role = $user->roles()->wherePivot('customer_id', $this->input['customer_id'])->get()->first();


        if($activation->role_id != $role->id)
        {
            return NULL;
        }

        $this->payload = $role;
        
    }
    
} 