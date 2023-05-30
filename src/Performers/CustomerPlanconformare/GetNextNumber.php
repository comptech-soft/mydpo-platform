<?php

namespace MyDpo\Performers\CustomerPlanconformare;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerPlanconformare;

class GetNextNumber extends Perform {

    public function Action() {

        
        $sql = "
            SELECT 
                MAX(CAST(`number` AS UNSIGNED)) as max_number 
            FROM `customers-planuri-conformare` 
            WHERE (customer_id=" . $this->customer_id . ")"
        ;

        $records = \DB::select($sql);

        $this->payload =  1 + (count($records) > 0 ? $records[0]->max_number : 0);
    
    }
}