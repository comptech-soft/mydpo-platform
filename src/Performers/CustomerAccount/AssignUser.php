<?php

namespace MyDpo\Performers\CustomerAccount;

use MyDpo\Helpers\Perform;
use MyDpo\Models\RoleUser;
use MyDpo\Models\Activation;
use MyDpo\Models\CustomerAccount;
use MyDpo\Events\CustomerPersons\CustomerPersonCreateAccount;

class AssignUser extends Perform {

    public function Action() {

        $accountInput = [
            'customer_id' => $this->input['customer_id'],
            'user_id' => $this->input['user_id'],
            'department_id' => $this->input['department_id'],
            'newsletter' => $this->input['newsletter'],
            'locale' => $this->input['locale'],
            'role_id' => $this->input['role_id'],
        ];

        $account = CustomerAccount::create($accountInput);

        $roleUser = RoleUser::CreateAccountRole(
            $this->input['customer_id'], 
            $accountInput['user_id'], 
            $account->role_id,
        );

        event(new CustomerPersonCreateAccount([
            ...$accountInput,
            'account' => $account,
            'roleUser' => $roleUser,
        ]));

        $this->payload = $account;

    }
}