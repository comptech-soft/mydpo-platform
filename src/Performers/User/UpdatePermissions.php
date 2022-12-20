<?php

namespace MyDpo\Performers\User;

use MyDpo\Helpers\Perform;
use MyDpo\Models\User;

class UpdatePermissions extends Perform {

    public function Action() {
        $user = User::find($this->input['user_id']);

        $user->update([
            'permissions' => $this->input['permissions'],
        ]);

    }

} 