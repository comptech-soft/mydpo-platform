<?php

namespace MyDpo\Performers\CustomerCurs;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerCursUser;

class DesasociereUtilizatori extends Perform {

    public function Action() {

        foreach($this->input['ids'] as $i => $id)
        {
            $record = CustomerCursUser::find($id);

            $record->removeRecord();
        }
    
    }
}