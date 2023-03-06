<?php

namespace MyDpo\Performers\CustomerRegister;

use MyDpo\Helpers\Perform;
use MyDpo\Exports\CustomerRegisterExport\Exporter;

class ImportRegister extends Perform {

    public function Action() {

       dd($this->input);
    }

    
}