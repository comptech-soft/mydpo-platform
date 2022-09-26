<?php

namespace MyDpo\Performers\CustomerFile;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerFile;

class MoveFiles extends Perform {
  
    public function Action() {

        dd($this->input);
        // foreach($this->input['files'] as $i => $file_id)
        // {
        //     $record = CustomerFile::find($file_id);

        //     $record->status = $this->input['status'];

        //     if(false)
        //     {
        //         $this->SendNotify();
        //     }

        //     $record->save();
        // }

        // activity()
        //     ->by(\Auth::user())
        //     ->on($record)
        //     ->withProperties(
        //         [
        //             'input' => request()->all(),
        //             'ip' => request()->ip(),
        //         ]
        //     )
        //     ->event(__CLASS__)
        //     ->createdAt($now = now())
        //     ->log(
        //         __(
        //             ':name a schimbat statusul fișierelor', 
        //             [
        //                 'name' => \Auth::user()->full_name 
        //             ]
        //         ), 
        //     );
        
    }

} 