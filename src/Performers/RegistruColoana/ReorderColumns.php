<?php

namespace MyDpo\Performers\RegistruColoana;

use MyDpo\Helpers\Perform;
use MyDpo\Models\RegistruColoana;

class ReorderColumns extends Perform {

    public function Action() {

        dd(__METHOD__);
        // $role = Role::find($this->input['id']);

        // if(! $role->permissions) {
        //     $permissions = $this->input['permissions'];
        // }
        // else
        // {
        //     $permissions = [
        //         ...$role->permissions,
        //         ...$this->input['permissions'],
        //     ];
        // }

        // $role->permissions = $permissions;

        // $role->save();
    
    }
}