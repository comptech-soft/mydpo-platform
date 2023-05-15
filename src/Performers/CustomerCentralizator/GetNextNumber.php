<?php

namespace MyDpo\Performers\CustomerCentralizator;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerCentralizator;

class GetNextNumber extends Perform {

    /**
     * Cate centralizator are asociate un customer
     */
    public function Action() {

        
        $sql = "
            SELECT 
                MAX(CAST(`number` AS UNSIGNED)) as max_number 
            FROM `customers-centralizatoare` 
            WHERE (customer_id=" . $this->customer_id . " AND centralizator_id=" . $this->centralizator_id . ")"
        ;

        $records = \DB::select($sql);

        $this->payload =  1 + (count($records) > 0 ? $records[0]->max_number : 0);
    
    }
}