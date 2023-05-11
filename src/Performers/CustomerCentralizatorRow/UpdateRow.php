<?php

namespace MyDpo\Performers\CustomerCentralizatorRow;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerCentralizatorRow;
use MyDpo\Models\CustomerCentralizatorRowValue;

class UpdateRow extends Perform {

    public function Action() {

        $input = collect($this->input)->except(['rowvalues'])->toArray();

        $record = CustomerCentralizatorRow::find($this->id);
        $record->update($input);

        foreach($this->rowvalues as $i => $input)
        {
            $rowvalue = CustomerCentralizatorRowValue::find($input['id']);

            $rowvalue->update($input);
        }

        $this->payload = [
            'record' => CustomerCentralizatorRow::find($record->id),
        ];
    
    }
}