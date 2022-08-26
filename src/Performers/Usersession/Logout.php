<?php

namespace MyDpo\Performers\Usersession;

use MyDpo\Helpers\Perform;

class Logout extends Perform {


    public function Action() {
    
        \Auth::guard('web')->logout();
 
        request()->session()->invalidate();
        request()->session()->regenerateToken();

    }

} 