<?php

namespace MyDpo\Performers\CustomerAccount;

use MyDpo\Helpers\Perform;
use MyDpo\Models\RoleUser;

class UpdateRole extends Perform {

    public function Action() {

        RoleUser::CreateAccountRole(
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
                $activation = createActivation($this->input['user_id'], $this->input['customer_id'], $this->input['role_id']);

                dd($activation);
            }
        }
    
    }
}