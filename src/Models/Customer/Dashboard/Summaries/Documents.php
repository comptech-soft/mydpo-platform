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
                    (`customers-folders`.`type` = 'documente') AND
                    (`customers-files`.`customer_id` = " . $customer_id . ")
                ";

            $records = \DB::select($sql);
        
            return $records[0]->count_records;
        }

        // 'customers-folders-permissions'
        
        return 198;
    } 

}