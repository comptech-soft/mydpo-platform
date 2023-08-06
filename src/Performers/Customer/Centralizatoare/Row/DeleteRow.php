<?php

namespace MyDpo\Performers\Customer\Centralizatoare\Row;

use MyDpo\Helpers\Perform;
use MyDpo\Models\Customer\CustomerCentralizatorRow;
use MyDpo\Models\Customer\CustomerCentralizatorRowValue;

class DeleteRow extends Perform {

    public function Action() {

        $values = CustomerCentralizatorRowValue::where('row_id', $this->id)->delete();
        $record = CustomerCentralizatorRow::where('id', $this->id)->delete();

        $this->payload = [
            'record' => $record,
        ];
    
    }
}