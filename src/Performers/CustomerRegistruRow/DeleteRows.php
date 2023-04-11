<?php

namespace MyDpo\Performers\CustomerRegistruRow;

use MyDpo\Helpers\Perform;
// use MyDpo\Models\CustomerRegistruRow;
// use MyDpo\Models\CustomerRegistruRowValue;

class DeleteRows extends Perform {

    public function Action() {


        dd($this->input);

        // if(! is_array($this->input['row_id']) )
        // {
        //     $this->input['row_id'] = [$this->input['row_id']];
        // }

        // foreach($this->input['row_id'] as $i => $row_id)
        // {
        //     $row = CustomerRegistruRow::find($row_id);
        //     $row->status = $this->input['status'];
        //     $row->save();

        //     $value = CustomerRegistruRowValue::where('row_id', $row_id)->where('type', 'STATUS')->first();
        //     $value->value = $this->input['status'];
        //     $value->save();
        // }
        
    }
}