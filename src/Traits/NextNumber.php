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
            WHERE type='aaaa'"
        ;

        return $sql;
    }
}