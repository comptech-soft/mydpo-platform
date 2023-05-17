<?php

namespace MyDpo\Performers\CustomerCentralizator;

use MyDpo\Helpers\Perform;
use MyDpo\Exports\CustomerCentralizator\Exporter;
// use MyDpo\Models\CustomerCentralizator;

class Export extends Perform {

    public function Action() {

        
        
        // $departamente_ids = [];
        // if( array_key_exists('departamente_ids', $this->input) )
        // {
        //     $departamente_ids = $this->input['departamente_ids'];
        // }

        $exporter = new Exporter();

        // dd($this->file_name);

        // $this->createUserFolder();

        $file_name = 'storage/exports/' . \Auth::user()->id. '/' . $this->file_name;

        // dd(asset($file_name));

        // $file = \Str::replace('public', 'storage', $filename);

        \Excel::store($exporter, $file_name, NULL, NULL, ['visibility' => 'public']);

        $this->payload = [
            'record' => [
                'url' => asset($file_name),
            ],
        ];
    
    }
}