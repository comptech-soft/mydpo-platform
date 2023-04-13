<?php

namespace MyDpo\Performers\CustomerAccount;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerAccount;

class SavePermissions extends Perform {

    public function Action() {

        dd($this->input);

        $role = Role::find($this->input['id']);

        if(! $role->permissions) {
            $permissions = $this->input['permissions'];
        }
        else
        {
            $permissions = [
                ...$role->permissions,
                ...$this->input['permissions'],
            ];
        }

        $role->permissions = $permissions;

        $role->save();
    
    }
}