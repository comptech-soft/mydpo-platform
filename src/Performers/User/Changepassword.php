<?php

namespace MyDpo\Performers\User;

use MyDpo\Helpers\Perform;
use MyDpo\Models\User;

class Changepassword extends Perform {

    public function Action() {
        
        $user = User::find($this->input['id']);

        $user->update([
            'password' => \Hash::make($this->input['password'])
        ]);

    }

} 