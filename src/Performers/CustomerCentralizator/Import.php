<?php

namespace MyDpo\Performers\CustomerCentralizator;

use MyDpo\Helpers\Perform;
use MyDpo\Exports\CustomerCentralizator\Exporter;
use MyDpo\Imports\CustomerCentralizator\Importer;

class Import extends Perform {

    public function Action() {

        $importer = new Importer($this->input);

        \Excel::import($importer, $this->file);
    
    }
}