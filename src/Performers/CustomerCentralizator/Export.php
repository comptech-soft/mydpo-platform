<?php

namespace MyDpo\Performers\CustomerCentralizator;

use MyDpo\Helpers\Perform;
use MyDpo\Exports\CustomerCentralizator\Exporter;

class Export extends Perform {

    public function Action() {

        $exporter = new Exporter($this->department_ids, $this->id);

        $file_name = 'public/exports/' . \Auth::user()->id. '/' . $this->file_name;

        $file = \Str::replace('public', 'storage', $file_name);

        \Excel::store($exporter, $file_name, NULL, NULL, ['visibility' => 'public']);

        $this->payload = [
            'record' => [
                'url' => asset($file),
            ],
        ];
    
    }
}