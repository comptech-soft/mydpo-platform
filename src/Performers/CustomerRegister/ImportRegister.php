<?php

namespace MyDpo\Performers\CustomerRegister;

use MyDpo\Helpers\Perform;
use MyDpo\Imports\CustomerRegister\Importer;

class ImportRegister extends Perform {

    public function Action() {

        $importer = new Importer($this->input);

        \Excel::import($importer, $this->input['file']);

    }
    
}