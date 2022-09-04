<?php

namespace MyDpo\Performers\Traits;

use MyDpo\Helpers\Perform;

class GetNextNumber extends Perform {

    public function Action() {
        
        $model = $this->input['model'];

        $instance = new $model();

        $table = $instance->getTable();

        $sql = "
            SELECT 
                MAX(CAST(`" . $instance->nextNumberColumn . "` AS UNSIGNED)) as max_number 
            FROM `" . $table . "` 
            WHERE " . call_user_func([$model, 'nextNumberWhere'], $this->input)
        ;

        $records = \DB::select($sql);

        $this->payload =  1 + (count($records) > 0 ? $records[0]->max_number : 0);
    }

} 