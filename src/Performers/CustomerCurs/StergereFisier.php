<?php

namespace MyDpo\Performers\CustomerCurs;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerCursFile;

class StergereFisier extends Perform {

    public function Action() {

        $record = CustomerCursFile::find($this->input['id']);
        $record->delete();
    
    }
}