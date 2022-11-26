<?php

namespace MyDpo\Performers\CustomerAccount;

use MyDpo\Helpers\Perform;
use MyDpo\Models\RoleUser;
use MyDpo\Models\Activation;
use MyDpo\Models\CustomerAccount;

class UpdateStatus extends Perform {

    public function Action() {

        $account = CustomerAccount::find($this->input['id']);

        $roleuser = RoleUser::CreateAccountRole(
            $this->input['customer_id'], 
            $this->input['user_id'], 
            $this->input['role_id']
        );

        $activation = Activation::createActivation($this->input['user_id'], $this->input['customer_id'], $this->input['role_id']);

        if($activation->activated == 1)
        {
            $activation->activated = 0;
            $activation->activated_at = NULL;

            $account->activated = 0;
            $account->activated_at = NULL;
        }
        else
        {
            $activation->activated = 1;
            $activation->activated_at = \Carbon\Carbon::now();

            $account->activated = 1;
            $account->activated_at = \Carbon\Carbon::now();
        }

        $account->role_id = $this->input['role_id'];
        
        $activation->save();
        $account->save();
    
    }
}