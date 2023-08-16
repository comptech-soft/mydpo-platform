<?php

namespace MyDpo\Models\Customer\Dashboard\Summaries;

class AnalizaGap {

    public static function CountLivrabile($customer_id) {

        $sql = "
            SELECT
                COUNT(*) AS count_records
            FROM `customers-files`
            LEFT JOIN `customers-folders`
            ON `customers-folders`.`id` = `customers-files`.`folder_id`
            WHERE 
                (`customers-folders`.`type` = 'analizagap') AND
                (`customers-files`.`customer_id` = " . $customer_id . ")
            ";

        $records = \DB::select($sql);

        return $records[0]->count_records;
    } 

}