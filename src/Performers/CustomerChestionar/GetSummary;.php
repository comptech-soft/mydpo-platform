<?php

namespace MyDpo\Performers\CustomerChestionar;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerChestionar;

class GetSummary extends Perform {

    /**
     * Cate chestionar are asociate un customer
     */
    public function Action() {

        $customer_id = $this->input['customer_id'];
        
        $count = CustomerChestionar::where('customer_id', $customer_id)->has('chestionar')->count();
        
        $this->payload = [

            'count' => $count,

        ];
    
    }
}