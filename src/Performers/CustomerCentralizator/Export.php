<?php

namespace MyDpo\Performers\CustomerCentralizator;

use MyDpo\Helpers\Perform;
use MyDpo\Exports\CustomerCentralizator\Exporter;
// use MyDpo\Models\CustomerCentralizator;

class Export extends Perform {

    public function Action() {

        
        dd($this->input, __METHOD__);
    
    }
}