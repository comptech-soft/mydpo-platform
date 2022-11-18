<?php

namespace MyDpo\Performers\CustomerAccount;

use MyDpo\Helpers\Perform;
use MyDpo\Models\RoleUser;

class UpdateRole extends Perform {

    public function Action() {

        RoleUser::CreateAccountRole(
            $input['customer_id'], 
            $input['user_id'], 
            $input['role_id']
        );
    
    }
}