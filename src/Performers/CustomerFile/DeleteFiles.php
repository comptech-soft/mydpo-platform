<?php

namespace MyDpo\Performers\CustomerFile;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerFile;

class DeleteFiles extends Perform {

    public function Action() {

        CustomerFile::whereIn('id',  $this->input['files'])->delete();

        activity()
            ->by(\Auth::user())
            ->on(NULL)
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
                    ':name a șters fișierele', 
                    [
                        'name' => \Auth::user()->full_name 
                    ]
                ), 
            );
        
    }

} 