<?php

namespace MyDpo\Performers\CustomerCurs;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerCurs;

class GetSummary extends Perform {

    /**
     * Cate cursuri are asociate un customer
     */
    public function Action() {

        $customer_id = $this->input['customer_id'];
        
        $count = CustomerCurs::where('customer_id', $customer_id)->count();
        
        $this->payload = [

            'count' => $count,

        ];
    
    }
}