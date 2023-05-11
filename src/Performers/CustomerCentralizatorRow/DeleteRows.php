<?php

namespace MyDpo\Performers\CustomerCentralizatorRow;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerCentralizatorRow;
use MyDpo\Models\CustomerCentralizatorRowValue;

class DeleteRows extends Perform {

    public function Action() {
      
        $records = NULL;

        if(!! count($this->selected_rows) )
        {
            

            $values = CustomerCentralizatorRowValue::whereIn('row_id', $this->selected_rows)->delete();
            $records = CustomerCentralizatorRow::whereIn('id', $this->selected_rows)->delete();
        }

        $this->payload = [
            'record' => $records,
        ];
    
    }
}