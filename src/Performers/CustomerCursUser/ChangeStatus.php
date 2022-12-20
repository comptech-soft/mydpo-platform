<?php

namespace MyDpo\Performers\CustomerCursUser;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerCursUser;

class ChangeStatus extends Perform {


    public function Action() {

        $record = CustomerCursUser::find($this->input['id']);

        $record->status = $this->input['status'];
        $record->save();
        
        $this->payload = $record;
    
    }
}