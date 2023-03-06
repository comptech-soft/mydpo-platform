<?php

namespace MyDpo\Performers\CustomerRegister;

use MyDpo\Helpers\Perform;
use MyDpo\Exports\CustomerRegisterExport\Exporter;

class ExportRegister extends Perform {

    public function Action() {

        $exporter = new Exporter($this->input['id']);

        // storage/app
        \Excel::store($exporter, $file = 'aaaa.xlsx', NULL, NULL, ['visibility' => 'public']);

        $this->payload = $url = asset($file);


    
    }
}