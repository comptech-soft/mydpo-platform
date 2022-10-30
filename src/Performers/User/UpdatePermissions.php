<?php

namespace MyDpo\Performers\User;

use MyDpo\Helpers\Perform;
use MyDpo\Models\User;

class UpdatePermissions extends Perform {

    public function Action() {

        dd($this->input);
        
        $user = User::find($this->input['id']);

        $user->update([
            'password' => \Hash::make($this->input['password'])
        ]);

    }

} 