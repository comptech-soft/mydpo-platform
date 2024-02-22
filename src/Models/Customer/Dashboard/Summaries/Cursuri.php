<?php

namespace MyDpo\Models\Customer\Dashboard\Summaries;

class Cursuri {

    public static function CountLivrabile($customer_id) {


        if( config('app.platform') == 'admin')
        {
            $sql = "
                SELECT
                    COUNT(*) AS count_records
                FROM `customers-cursuri`
                WHERE 
                    (`customers-cursuri`.`customer_id` = " . $customer_id . ")
                ";

            $records = \DB::select($sql);

            return $records[0]->count_records;
        }

        $sql = "
            SELECT
                COUNT(*) AS count_records
            FROM `customers-cursuri-users`
            WHERE 
                (`customers-cursuri-users`.`customer_id` = " . $customer_id . ") AND
                (`customers-cursuri-users`.`user_id` = " . \Auth::user()->id . ")
            ";

        $records = \DB::select($sql);

        return $records[0]->count_records;
    } 

}