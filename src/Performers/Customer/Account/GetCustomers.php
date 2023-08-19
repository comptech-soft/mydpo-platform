<?php

namespace MyDpo\Performers\Customer\Account;

use MyDpo\Helpers\Perform;
use MyDpo\Models\Customer\Accounts\Account;
use MyDpo\Models\Customer\Customer;

class GetCustomers extends Perform {

    public function Action() {

        $customers = Customer::whereIn('id', Account::distinct()->pluck('customer_id'))->orderBy('name')->get();
        
        $this->payload = [
            'customers' => $customers,
        ];
    }
       
}