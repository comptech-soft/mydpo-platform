<?php

namespace MyDpo\Performers\CustomerRegistruRow;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerRegistruRowFile;

class LoadFiles extends Perform {

    public function Action() {

        $files = CustomerRegistruRowFile::where('row_id', $this->input['row_id'])->get();
        
        $this->payload = [
            'files' => $files,
        ];
        
    }
}