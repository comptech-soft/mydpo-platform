<?php

namespace MyDpo\Models\Customer\Dashboard\Summaries;

class PlanConformare {

    public static function CountLivrabile($customer_id) {


        $sql = "
            SELECT
                COUNT(*) AS count_records
            FROM `customers-planuri-conformare`
            WHERE 
                (`customers-planuri-conformare`.`customer_id` = " . $customer_id . ")
            ";

        if( config('app.platform') == 'b2b')
        {
            $sql .= " AND (`customers-planuri-conformare`.`visibility` = 1)";
        }

        $records = \DB::select($sql);

        return $records[0]->count_records;
    } 

}