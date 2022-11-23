<?php

namespace MyDpo\Performers\CustomerAccount;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerAccount;

class SaveFolderPermissions extends Perform {

    public function Action() {

        $account = CustomerAccount::where('user_id', $this->input['user_id'])->where('customer_id', $this->input['customer_id'])->first();

        $account->permissions = [
            ...$account->permissions,
            $this->input['key'] => $this->input['selected'][$this->input['key']],
        ];

        $account->save();
    
    }
}