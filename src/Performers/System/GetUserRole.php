<?php

namespace MyDpo\Performers\System;

use MyDpo\Helpers\Perform;
// use MyDpo\Models\SysConfig;
// use MyDpo\Models\Platform;
// use MyDpo\Models\MaterialStatus;
// use MyDpo\Models\Language;
// use MyDpo\Models\Role;

class GetUserRole extends Perform {

    public function Action() {
    
        $user = \Auth::user();

        dd($user);
    }

   
} 