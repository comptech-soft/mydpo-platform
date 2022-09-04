<?php

namespace MyDpo\Traits;

trait NextNumber { 

    public static function getNextNumber($input) {

        $table = (new static)->getTable();

        $sql = "
            SELECT 
                MAX(CAST(`number` AS UNSIGNED)) as max_number 
            FROM `" . $table . "` 
            WHERE type='" . $input . "'"
        ;

        return $sql;
    }
}