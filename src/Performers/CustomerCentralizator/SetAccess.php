<?php

namespace MyDpo\Performers\CustomerCentralizator;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerCentralizator;

class SetAccess extends Perform {

    public function Action() {

        dd($this->input);

        $this->payload = [
            'record' => NULL,
        ];
    
    }
}