<?php

namespace MyDpo\Performers\CustomerCentralizatorRow;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerCentralizator;
use MyDpo\Models\CustomerCentralizatorRowValue;

class SetRowsVisibility extends Perform {

    public function Action() {

        $records = NULL;

        if(!! count($this->selected_rows) )
        {
            $customer_centralizator = CustomerCentralizator::find($this->customer_centralizator_id);

            $records = CustomerCentralizatorRowValue::where('column_id', $customer_centralizator->visible_column_id)
                ->whereIn('row_id', $this->selected_rows)
                ->update([
                    'value' => $this->visibility,
                ]);
        }

        $this->payload = [
            'record' => $records,
            'visible_column_id' => $customer_centralizator->visible_column_id,
            'input' => $this->input,
        ];
    
    }
}