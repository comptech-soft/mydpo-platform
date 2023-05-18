<?php

namespace MyDpo\Performers\CustomerCentralizatorRowFile;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerCentralizatorRowFile;

class DownloadFile extends Perform {

    public function Action() {

            dd($input);

        $this->payload = [
            'record' => NULL,
        ];
    }

}