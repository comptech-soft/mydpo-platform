<?php

namespace MyDpo\Performers\UserCustomer;

use MyDpo\Helpers\Perform;
use MyDpo\Models\UserCustomer;

class UpdateUserCustomers extends Perform {

    public function Action() {

        dd(__METHOD__, $this->input);
        
    }

} 