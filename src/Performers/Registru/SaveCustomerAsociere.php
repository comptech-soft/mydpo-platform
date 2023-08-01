<?php

namespace MyDpo\Performers\Registru;

use MyDpo\Helpers\Perform;
use MyDpo\Models\Customer\CustomerRegistruAsociat;

class SaveCustomerAsociere extends Perform {

    public function Action() {

        $customer_id = $this->customer_id; 

        foreach($this->registre as $i => $registru)
        {
            $input = [
                ...$registru,
                'customer_id' => $customer_id,
            ];

            CustomerRegistruAsociat::UpdateOrCreateAsociere($input);
        }
    }

}