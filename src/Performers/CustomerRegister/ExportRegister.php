<?php

namespace MyDpo\Performers\CustomerRegister;

use MyDpo\Helpers\Perform;
use MyDpo\Exports\CustomerRegisterExport\Exporter;
// use MyDpo\Models\CustomerRegistruAsociat;

class ExportRegister extends Perform {

    public function Action() {

        $exporter = new Exporter($this->input['id']);


        \Excel::store($exporter, 'aaaa.xlsx', NULL, NULL, ['visibility' => 'public']);
    
    }
}