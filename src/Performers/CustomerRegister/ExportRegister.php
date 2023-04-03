<?php

namespace MyDpo\Performers\CustomerRegister;

use MyDpo\Helpers\Perform;
use MyDpo\Exports\CustomerRegisterExport\Exporter;

class ExportRegister extends Perform {

    public function Action() {

        $exporter = new Exporter($this->input['id'], $this->input['juststructure'], $this->input['departamente_ids']);

        $this->createUserFolder();

        $filename = 'public/exports/' . \Auth::user()->id. '/' . $this->input['filename'] . '.xlsx';
        $file = \Str::replace('public', 'storage', $filename);

        \Excel::store($exporter, $filename, NULL, NULL, ['visibility' => 'public']);

        $this->payload = $url = asset($file);
    }

    public function createUserFolder() {
            
        if(! \Storage::exists('public/exports/' . \Auth::user()->id) )
        {
            \Storage::disk('public')->makeDirectory('exports/' . \Auth::user()->id, 0777);
        }
    }
}