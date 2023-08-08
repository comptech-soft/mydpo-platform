<?php

namespace MyDpo\Traits;

trait Numberable { 

    public static function doGetnextnumber($input, $record) {
        
        $instance = new (__CLASS__)();

        $table = $instance->getTable();

        $sql = "
            SELECT 
                MAX(CAST(`" . $instance->numberable['field'] . "` AS UNSIGNED)) as max_number 
            FROM `" . $table . "`";
        
        $where = $instance->numberable['where'];

        if(!! $where)
        {
            foreach($instance->numberable['replacement'] as $key => $field)
            {
                $where = \Str::replace($key, $input[$field], $where);
            }

            $sql .= (' WHERE ' . $where);
        }

        $records = \DB::select($sql);


        return 1 + (count($records) > 0 ? $records[0]->max_number : 0);
    }

}