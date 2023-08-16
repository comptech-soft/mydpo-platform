<?php

namespace MyDpo\Models\Customer\Dashboard\Summaries;

class Registre {

    public static function CountLivrabile($customer_id) {

        $sql = "
            SELECT
                COUNT(*) AS count_records
            FROM `customers-registers`
            WHERE 
                (`customers-registers`.`customer_id` = " . $customer_id . ")
            ";

        $records = \DB::select($sql);

        return $records[0]->count_records;
    } 

}