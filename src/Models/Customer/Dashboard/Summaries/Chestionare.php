<?php

namespace MyDpo\Models\Customer\Dashboard\Summaries;

class Chestionare {

    public static function CountLivrabile($customer_id) {

        if( config('app.platform') == 'admin')
        {
            $sql = "
                SELECT
                    COUNT(*) AS count_records
                FROM `customers-chestionare`
                WHERE 
                    (`customers-chestionare`.`customer_id` = " . $customer_id . ")
                ";

            $records = \DB::select($sql);

            return $records[0]->count_records;
        }

        $sql = "
            SELECT
                COUNT(*) AS count_records
            FROM `customers-chestionare-users`
            WHERE 
                (`customers-chestionare-users`.`customer_id` = " . $customer_id . ") AND
                (`customers-chestionare-users`.`user_id` = " . \Auth::user()->id . ")
            ";

        $records = \DB::select($sql);

        return $records[0]->count_records;

    } 

}