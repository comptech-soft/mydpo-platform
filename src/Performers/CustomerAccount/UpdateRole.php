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
    
    }
}