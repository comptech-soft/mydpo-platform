<?php

namespace MyDpo\Performers\CustomerPlanconformare;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerPlanconformareRow;

class SaveRows extends Perform {

    public function Action() {

        if($this->rows && is_array($this->rows))
        {
       
            foreach($this->rows as $i => $input)
            {
                $row = CustomerPlanconformareRow::find($input['id']);

                $row->update($input);
            }
        }
    }
}