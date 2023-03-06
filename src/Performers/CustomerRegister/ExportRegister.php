<?php

namespace MyDpo\Performers\CustomerRegister;

use MyDpo\Helpers\Perform;
use MyDpo\Exports\CustomerRegisterExport\Exporter;

class ExportRegister extends Perform {

    public function Action() {

        $exporter = new Exporter($this->input['id']);

        
        $this->createUserFolder();

        \Excel::store($exporter, $file = 'aaaa.xlsx', NULL, NULL, ['visibility' => 'public']);

        $this->payload = $url = asset($file);


    
    }

    public function createUserFolder() {
            
        if(! \Storage::exists('public/exports/' . \Auth::user()->id) )
        {
            \Storage::disk('public')->makeDirectory('exports/' . \Auth::user()->id, 0777);
        }
    }
}