<?php

namespace MyDpo\Traits;

trait NextNumber { 

    public static function getNextNumber($input) {

        $table = (new static)->getTable();

        $sql = "
            SELECT 
                MAX(CAST(`" . self::$nextNumberColumn . "` AS UNSIGNED)) as max_number 
            FROM `" . $table . "` 
            WHERE type='aaaa'"
        ;

        return $sql;
    }
}