<?php

namespace MyDpo\Performers\CustomerCentralizator;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerCentralizator;

class GetSummary extends Perform {

    /**
     * Cate cursuri are asociate un customer
     */
    public function Action() {

        $customer_id = $this->input['customer_id'];
        
        $count = CustomerCentralizator::where('customer_id', $customer_id)->has('centralizator')->count();
        
        $this->payload = [

            'count' => $count,

        ];
    
    }
}