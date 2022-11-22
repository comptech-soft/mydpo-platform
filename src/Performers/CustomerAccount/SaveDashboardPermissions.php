<?php

namespace MyDpo\Performers\CustomerAccount;

use MyDpo\Helpers\Perform;
use MyDpo\Models\RoleUser;
use MyDpo\Models\Activation;
use MyDpo\Models\CustomerAccount;

class SaveDashboardPermissions extends Perform {

    public function Action() {

        dd($this->input, __METHOD__);
    
    }
}