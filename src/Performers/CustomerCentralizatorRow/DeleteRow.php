<?php

namespace MyDpo\Performers\CustomerCentralizatorRow;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerCentralizatorRow;
use MyDpo\Models\CustomerCentralizatorRowValue;

class DeleteRow extends Perform {

    public function Action() {

        $values = CustomerCentralizatorRowValue::where('row_id', $this->id)->delete();
        $record = CustomerCentralizatorRow::where('id', $this->id)->delete();


        $this->payload = [
            'record' => $record,
        ];
    
    }
}