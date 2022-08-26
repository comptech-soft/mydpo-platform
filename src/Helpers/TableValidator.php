<?php

namespace MyDpo\Helpers;

class TableValidator {

    public static function columnValueExists($table, $column, $value) {
        $result = \DB::table($table)->where($column, $value)->first();        
        return $result ? 1 : 0;
    }

    public static function columnValueUnique($table, $column, $value) {
        return 1 - self::columnValueExists($table, $column, $value);
    }
    
}