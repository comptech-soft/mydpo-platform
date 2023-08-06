<?php

namespace MyDpo\Performers\Customer\Centralizatoare\Dashboard;

use MyDpo\Helpers\Perform;
use MyDpo\Models\Customer\Centralizatoare\CustomerCentralizatorAsociat;

class SaveCustomerAsociere extends Perform {

    public function Action() {

        $customer_id = $this->customer_id; 

        foreach($this->centralizatoare as $i => $centralizator)
        {
            $input = [
                ...$centralizator,
                'customer_id' => $customer_id,
            ];

            CustomerCentralizatorAsociat::UpdateOrCreateAsociere($input);
        }
    }

}