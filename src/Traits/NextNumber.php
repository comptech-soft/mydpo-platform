<?php

namespace MyDpo\Traits;

trait NextNumber { 

    public static function getNextNumber($input) {

        $instance = new static; 
        $table = $instance->getTable();

        $sql = "
            SELECT 
                MAX(CAST(`" . $instance->nextNumberColumn . "` AS UNSIGNED)) as max_number 
            FROM `" . $table . "` 
            WHERE " . $instance->nextNumberWhere($input)
        ;

        $records = \DB::select($sql);

        if( count($records) == 0)
        {
            return 1;
        }
        return 1 + $records[0]->max_number;
    }
}