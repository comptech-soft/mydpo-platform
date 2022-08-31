<?php

namespace MyDpo\Performers\CustomerFolder;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerFolder;

class GetSummary extends Perform {

    public function Action() {

        
        $this->payload = __METHOD__;
    
    }
}