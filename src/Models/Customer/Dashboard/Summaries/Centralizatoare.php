<?php

namespace MyDpo\Models\Customer\Dashboard\Summaries;

class Centralizatoare {

    public static function CountLivrabile($customer_id) {

        $sql = "
            SELECT
                COUNT(*) AS count_records
            FROM `customers-centralizatoare`
            WHERE 
                (`customers-centralizatoare`.`customer_id` = " . $customer_id . ")
            ";

        $records = \DB::select($sql);

        return $records[0]->count_records;
    } 

}