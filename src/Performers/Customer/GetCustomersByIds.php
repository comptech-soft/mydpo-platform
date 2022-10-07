<?php

namespace MyDpo\Performers\Customer;

use MyDpo\Helpers\Perform;
// use MyDpo\Models\CustomerFile;

class GetCustomersByIds extends Perform {

   
    public function Action() {

        dd(__METHOD__, $this->input);
    }

} 