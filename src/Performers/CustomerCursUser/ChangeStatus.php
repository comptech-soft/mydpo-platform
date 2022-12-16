<?php

namespace MyDpo\Performers\CustomerCursUser;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerCursUser;

class ChangeStatus extends Perform {

    /**
     * Cate cursuri are asociate un customer
     */
    public function Action() {

        $record = CustomerCursUser::find($this->input['id']);

        if($record->status == 'sended')
        {
            $record->status = 'started';
        }

        $record->save();
        
        $this->payload = $record;
    
    }
}