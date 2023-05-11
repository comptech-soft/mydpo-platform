<?php

namespace MyDpo\Performers\CustomerCentralizatorRow;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerCentralizatorRow;
use MyDpo\Models\CustomerCentralizatorRowValue;

class DeleteRow extends Perform {

    public function Action() {


        dd($this->input);
        // $input = collect($this->input)->except(['rowvalues'])->toArray();

        // $record = CustomerCentralizatorRow::create($input);

        // foreach($this->rowvalues as $i => $input)
        // {
        //     $input['row_id'] = $record->id;

        //     CustomerCentralizatorRowValue::create($input);
        // }

        // $this->payload = [
        //     'record' => CustomerCentralizatorRow::find($record->id),
        // ];
    
    }
}