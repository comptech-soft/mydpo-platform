<?php

namespace MyDpo\Traits;

// use MyDpo\Performers\Traits\GetNextNumber;

trait Numberable { 

    public static function doGetnextnumber($input, $record) {
        
        $instance = new (__CLASS__)();

        dd($instance);

        return 7;
    }

}

/**
 * 
 * $model = $this->model;

        

        $table = $instance->getTable();

        $sql = "
            SELECT 
                MAX(CAST(`" . $instance->nextNumberColumn . "` AS UNSIGNED)) as max_number 
            FROM `" . $table . "`" 
        ;

        if(method_exists($this->model, 'NextNumberWhere'))
        {
            $where = " WHERE " . call_user_func([$model, 'NextNumberWhere'], $this->input);
            $sql .= $where;
        }

        $records = \DB::select($sql);

        $this->payload =  1 + (count($records) > 0 ? $records[0]->max_number : 0);
 */