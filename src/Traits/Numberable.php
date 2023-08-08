<?php

namespace MyDpo\Traits;

// use MyDpo\Performers\Traits\GetNextNumber;

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

            dd($where);
        }

        dd($sql);
        
        dd($table, $input, $instance->numberable);

        return 7;
    }

}

/**
 * 
 * $model = $this->model;

        

        

        

        if(method_exists($this->model, 'NextNumberWhere'))
        {
            $where = " WHERE " . call_user_func([$model, 'NextNumberWhere'], $this->input);
            $sql .= $where;
        }

        $records = \DB::select($sql);

        $this->payload =  1 + (count($records) > 0 ? $records[0]->max_number : 0);
 */