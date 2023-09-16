<?php

namespace MyDpo\Performers\CustomerCursUser;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerCursUser;

class ChangeStatus extends Perform {


    public function Action() {

        $record = CustomerCursUser::find($this->input['id']);

        $record->status = $this->input['status'];

        $record->done_at = \Carbon\Carbon::now();

        $record->save();
        
        $this->payload = $record;
    
    }
}