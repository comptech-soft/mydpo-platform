<?php

namespace MyDpo\Performers\Customer;

use MyDpo\Helpers\Perform;
use MyDpo\Models\Customer;

class GetCustomersByIds extends Perform {

   
    public function Action() {

        $ids = array_key_exists('ids', $this->input) ? $this->input['ids'] : [];

        if( count($ids) == 0 )
        {
            $customers = [];
        }
        else
        {
            $customers = Customer::whereIn('id', $ids)->get();
        }

        $this->payload = $customers;
    }

} 