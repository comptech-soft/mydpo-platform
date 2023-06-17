<?php

namespace MyDpo\Performers\Translation;

use MyDpo\Helpers\Perform;
use MyDpo\Exports\Translation\Exporter;

class Export extends Perform {

    public function Action() {
	
		$this->createUserFolder();
		
        // $exporter = new Exporter();
		
		$file_name = 'public/exports/' . \Auth::user()->id. '/' . $this->file_name;
		
		$result = \Excel::store(
            new Exporter(), 
            $file_name, 
            NULL, 
            NULL, 
            ['visibility' => 'public']
        );

        dd($result);
			
		$file = \Str::replace('public', 'storage', $file_name);
		
        $this->payload = [
            'record' => [
                'url' => asset($file),
            ],
        ];

    }
	
	public function createUserFolder() {            
        if(! \Storage::exists('public/exports/' . \Auth::user()->id) )
        {
            \Storage::disk('public')->makeDirectory('exports/' . \Auth::user()->id, 0777);
        }
    }
	
}