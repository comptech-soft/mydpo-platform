<?php

namespace MyDpo\Performers\Translation;

use MyDpo\Helpers\Perform;
// use MyDpo\Exports\CustomerCentralizator\Exporter;

class Export extends Perform {

    public function Action() {

        dd($this->input);
		
		// $this->createUserFolder();
		
        // $exporter = new Exporter($this->department_ids, $this->id);
		
		// $file_name = 'public/exports/' . \Auth::user()->id. '/' . $this->file_name;
		
		// \Excel::store($exporter, $file_name, NULL, NULL, ['visibility' => 'public']);
			
		// $file = \Str::replace('public', 'storage', $file_name);
		
        // $this->payload = [
        //     'record' => [
        //         'url' => asset($file),
        //     ],
        // ];

    }
	
	public function createUserFolder() {
            
        // if(! \Storage::exists('public/exports/' . \Auth::user()->id) )
        // {
        //     \Storage::disk('public')->makeDirectory('exports/' . \Auth::user()->id, 0777);
        // }
    }
	
}