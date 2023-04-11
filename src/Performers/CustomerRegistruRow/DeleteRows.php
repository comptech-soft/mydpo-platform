<?php

namespace MyDpo\Performers\CustomerRegistruRow;

use MyDpo\Helpers\Perform;

class DeleteRows extends Perform {

    public function Action() {
        if(! is_array($this->input['row_id']) )
        {
            $this->input['row_id'] = [$this->input['row_id']];
        }

        foreach($this->input['row_id'] as $i => $row_id)
        {
            $row = CustomerRegistruRow::find($row_id);
            $row->deleteRowWithValues();
        }
        
    }
}