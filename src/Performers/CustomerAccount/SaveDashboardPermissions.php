<?php

namespace MyDpo\Performers\CustomerAccount;

use MyDpo\Helpers\Perform;
use MyDpo\Models\RoleUser;
use MyDpo\Models\Activation;
use MyDpo\Models\CustomerAccount;

class SaveDashboardPermissions extends Perform {

    public function Action() {

        $account = CustomerAccount::where('user_id', $this->input['user_id'])->where('customer_id', $this->input['customer_id'])->first();

        $account->permissions = [
            ...$account->permissions,
            'dashboard-client' => $this->input['selected']['dashboard-client'],
        ];

        $account->save();
    
    }
}