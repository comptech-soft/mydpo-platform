<?php

namespace MyDpo\Performers\User;

use MyDpo\Helpers\Perform;
use MyDpo\Models\Authentication\User;

class UpdatePermissions extends Perform {

    public function Action() {

        $user = User::find($this->input['user_id']);

        if(! $user->permissions) {
            $permissions = $this->input['permissions'];
        }
        else
        {
            $permissions = [
                ...$user->permissions,
                ...$this->input['permissions'],
            ];
        }

        $user->update([
            'permissions' => $permissions,
        ]);

    }

} 