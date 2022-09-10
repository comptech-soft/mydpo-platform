<?php

namespace MyDpo\Performers\User;

use MyDpo\Helpers\Perform;

class Changepassword extends Perform {

    public function Action() {
        
        dd(__METHOD__, $this->input);
        // \Auth::guard('web')->logout();
 
        // request()->session()->invalidate();
        // request()->session()->regenerateToken();

    }

} 