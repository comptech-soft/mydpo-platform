<?php

namespace MyDpo\Performers\Role;

use MyDpo\Helpers\Perform;
use MyDpo\Models\Role;

class SaveRolePermissions extends Perform {

    public function Action() {

        $role = Role::find($this->input['id']);

        $role->permissions = [
            ...$role->permissions ? $role->permissions : [],
            $this->input['permissions'],
        ];

        $role->save();
    
    }
}