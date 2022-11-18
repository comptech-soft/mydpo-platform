<?php

namespace MyDpo\Performers\CustomerAccount;

use MyDpo\Helpers\Perform;
use MyDpo\Models\RoleUser;
use MyDpo\Models\Activation;

class UpdateRole extends Perform {

    public function Action() {

        $roleuser = RoleUser::CreateAccountRole(
            $this->input['customer_id'], 
            $this->input['user_id'], 
            $this->input['role_id']
        );


        if($this->input['checked'] == 1)
        {
            if($this->input['activated'] == 1)
            {

            }
            else
            {
                $activation = Activation::createActivation($this->input['user_id'], $this->input['customer_id'], $this->input['role_id']);

                $activation->activated = $roleuser->activated = 1;
                $activation->activated_at = $roleuser->activated_at = \Carbon\Carbon::now();
                $activation->save();
                $roleuser->save();
            }
        }
    
    }
}