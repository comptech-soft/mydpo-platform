<?php

namespace MyDpo\Performers\CustomerRegistruRow;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerRegistruRow;

class UploadFile extends Perform {

    public function Action() {
        
        dd($this->input);
        
    }
}