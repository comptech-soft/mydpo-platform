<?php

namespace MyDpo\Performers\User;

use MyDpo\Helpers\Perform;
use MyDpo\Models\User;

class UpdateStatus extends Perform {

    public function Action() {
      
        dd($this->input);
        // $user = User::find($this->input['user_id']);

        // $user->update([
        //     'permissions' => $this->input['permissions'],
        // ]);

    }

} 