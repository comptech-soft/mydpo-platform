<?php

namespace MyDpo\Performers\Customer\Centralizatoare\Row;

use MyDpo\Helpers\Perform;
use MyDpo\Models\Customer\Centralizatoare\CustomerCentralizatorRow;
use MyDpo\Models\Customer\Centralizatoare\CustomerCentralizatorRowValue;

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