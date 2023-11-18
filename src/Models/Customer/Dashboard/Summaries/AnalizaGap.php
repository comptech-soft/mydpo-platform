<?php

namespace MyDpo\Models\Customer\Dashboard\Summaries;

use MyDpo\Models\Customer\Documents\CustomerFolderPermission;

class Documents {

    public static function CountLivrabile($customer_id) {

        if( (config('app.platform') == 'admin') || ((config('app.platform') == 'b2b') && (\Auth::user()->role->id == 4)) )
        {
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

        $sql = "
            SELECT
                COUNT(*) AS count_records
            FROM `customers-files`
            LEFT JOIN `customers-folders`
                INNER JOIN `customers-folders-permissions`
                ON `customers-folders-permissions`.folder_id = `customers-folders`.id
            ON `customers-folders`.`id` = `customers-files`.`folder_id`
            WHERE 
                (`customers-folders`.`type` = 'analizagap') AND
                (`customers-files`.`customer_id` = " . $customer_id . ") AND
                (`customers-folders-permissions`.user_id = " . \Auth::user()->id . ") AND 
                (`customers-folders-permissions`.has_access = 1)
        ";
        
        $records = \DB::select($sql);
        
        return $records[0]->count_records;
    } 

}