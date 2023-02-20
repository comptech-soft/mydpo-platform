<?php

namespace MyDpo\Performers\RegistruColoana;

use MyDpo\Helpers\Perform;
use MyDpo\Models\RegistruColoana;

class ReorderColumns extends Perform {

    public function Action() {

        foreach($this->input as $i => $item)
        {
            $record = RegistruColoana::find($item['id']);
            $record->order_no = $item['order_no'];
            $record->save();
        }

    }
}