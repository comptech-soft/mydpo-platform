<?php

namespace MyDpo\Models\Customer\Dashboard\Summaries;

class Registre {

    public static function CountLivrabile($customer_id) {

        $wheres = [
            "(`customers-registers`.`customer_id` = " . $customer_id . ")",
            "(`registers`.`on_registre_page` = 1)",
        ];

        if( config('app.platform') == 'b2b' )
        {
            $wheres[] = "(`customers-registers`.`visibility` = 1)";
        }

        $sql = "
            SELECT
                COUNT(*) AS count_records
            FROM `customers-registers`
            LEFT JOIN `registers` ON `registers`.`id` = `customers-registers`.`register_id`
            WHERE " . implode(' AND ', $wheres);

        $records = \DB::select($sql);

        return $records[0]->count_records;
    } 

}