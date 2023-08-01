<?php

namespace MyDpo\Performers\Registru;

use MyDpo\Helpers\Perform;
use MyDpo\Models\Customer\CustomerCentralizatorAsociat;

class SaveCustomerAsociere extends Perform {

    public function Action() {

        dd($this->input);
        // $customer_id = $this->customer_id; 

        // foreach($this->centralizatoare as $i => $centralizator)
        // {
        //     $input = [
        //         ...$centralizator,
        //         'customer_id' => $customer_id,
        //     ];

        //     CustomerCentralizatorAsociat::UpdateOrCreateAsociere($input);
        // }
    }

}