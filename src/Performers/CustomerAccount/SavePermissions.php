<?php

namespace MyDpo\Performers\CustomerAccount;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerAccount;

class SavePermissions extends Perform {

    public function Action() {

        $account = CustomerAccount::find($this->input['account_id']);

        if(! $account->permissions) {
            $permissions = $this->input['permissions'];
        }
        else
        {
            $permissions = [
                ...$account->permissions,
                ...$this->input['permissions'],
            ];
        }

        $account->permissions = $permissions;

        $account->save();
    
    }
}