<?php

namespace MyDpo\Models\Customer\Dashboard\Summaries;

class Documents {

    public static function CountLivrabile($customer_id) {

        dd(\Auth::user()->role->toArray());
        if( (config('app.platform') == 'admin') || ((config('app.platform') == 'b2b') && (1 > 0)) )
        {
            $sql = "
                SELECT
                    COUNT(*) AS count_records
                FROM `customers-files`
                LEFT JOIN `customers-folders`
                ON `customers-folders`.`id` = `customers-files`.`folder_id`
                WHERE 
                    (`customers-folders`.`type` = 'documente') AND
                    (`customers-files`.`customer_id` = " . $customer_id . ")
                ";

            $records = \DB::select($sql);
        
            return $records[0]->count_records;
        }

        return -198;
    } 

}