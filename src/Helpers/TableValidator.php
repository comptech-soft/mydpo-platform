<?php

namespace MyDpo\Helpers;

class TableValidator {

    public static function columnValueExists($table, $column, $value, $id = NULL)  {

        $q = \DB::table($table)->where($column, $value);
        if($id)
        {
            $q->where('id', '<>', $id);
        }
        $result = $q->first();        
        return $result ? 1 : 0;
    }

    public static function columnValueUnique($table, $column, $value, $id = NULL) {
        return 1 - self::columnValueExists($table, $column, $value, $id);
    }
    
}