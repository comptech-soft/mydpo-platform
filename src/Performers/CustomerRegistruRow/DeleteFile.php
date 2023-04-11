<?php

namespace MyDpo\Performers\CustomerRegistruRow;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerRegistruRowFile;

class DeleteFile extends Perform {

    public function Action() {
        
        $record = CustomerRegistruRowFile::find($this->input['id']);

        $record->delete();
        
    }
}