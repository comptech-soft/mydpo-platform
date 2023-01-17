<?php

namespace MyDpo\Performers\CustomerCurs;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerCursParticipant;

class StergereParticipant extends Perform {

    public function Action() {

        $record = CustomerCursParticipant::find($this->input['id']);
        $record->delete();
    
    }
}