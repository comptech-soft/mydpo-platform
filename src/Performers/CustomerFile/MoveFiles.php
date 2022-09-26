<?php

namespace MyDpo\Performers\CustomerFile;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerFile;

class MoveFiles extends Perform {
  
    public function Action() {

        $files = CustomerFile::whereIn('id', $this->input['files'])->update([

            'folder_id' => $this->input['folder_id'],
            'updated_at' => \Carbon\Carbon::now(),
            'updated_by' => \Auth::user()->id,

        ]);
        
        activity()
            ->by(\Auth::user())
            ->withProperties(
                [
                    'input' => request()->all(),
                    'ip' => request()->ip(),
                ]
            )
            ->event(__CLASS__)
            ->createdAt($now = now())
            ->log(
                __(
                    ':name a mutat fișierele', 
                    [
                        'name' => \Auth::user()->full_name 
                    ]
                ), 
            );
        
    }

} 