<?php

namespace MyDpo\Performers\CustomerRegister;

use MyDpo\Helpers\Perform;
use MyDpo\Exports\CustomerRegisterExport\Exporter;
// use MyDpo\Models\CustomerRegistruAsociat;

class ExportRegister extends Perform {

    public function Action() {

        dd($this->input);

        $exporter = new Exporter();


        \Excel::store($exporter, 'aaaa.xlsx', NULL, NULL, ['visibility' => 'public']);
    
    }
}