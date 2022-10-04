<?php

namespace MyDpo\Performers\Role;

use MyDpo\Helpers\Perform;
use MyDpo\Models\Role;

class SaveRolePermissions extends Perform {

    public function Action() {

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