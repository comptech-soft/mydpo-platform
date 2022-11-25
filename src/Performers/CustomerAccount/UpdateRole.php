<?php

namespace MyDpo\Performers\CustomerAccount;

use MyDpo\Helpers\Perform;
use MyDpo\Models\RoleUser;
use MyDpo\Models\Activation;
use MyDpo\Models\CustomerAccount;

class UpdateRole extends Perform {

    public function Action() {


        $account = CustomerAccount::find($this->input['id']);

        $roleuser = RoleUser::CreateAccountRole(
            $this->input['customer_id'], 
            $this->input['user_id'], 
            $this->input['role_id']
        );

        if($this->input['checked'] == 1)
        {
            $activation = Activation::createActivation($this->input['user_id'], $this->input['customer_id'], $this->input['role_id']);

            $activation->activated = $account->activated = ($this->input['activated'] == 1 ? 0 : 1);
            $activation->activated_at = $account->activated_at = ($activation->activated == 0 ? NULL : \Carbon\Carbon::now());

            $activation->save();
            $account->save();
        }
    
    }
}