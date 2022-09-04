<?php

namespace MyDpo\Performers\Traits;

use MyDpo\Helpers\Perform;

class GetNextNumber extends Perform {


    public function Action() {
        
        $instance = $this->input['instance'];

        $table = $instance->getTable();

        $sql = "
            SELECT 
                MAX(CAST(`" . $instance->nextNumberColumn . "` AS UNSIGNED)) as max_number 
            FROM `" . $table . "` 
            WHERE " . $instance->nextNumberWhere($this->input)
        ;

        $records = \DB::select($sql);

        if( count($records) == 0)
        {
            return 1;
        }
        return 1 + $records[0]->max_number;
    }

} 