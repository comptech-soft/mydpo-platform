<?php

namespace MyDpo\Performers\CustomerFile;

use MyDpo\Helpers\Perform;
// use MyDpo\Models\UserSetting;

class ChangeFilesStatus extends Perform {

    public function Action() {

        dd($this->input);
        // $record = UserSetting::getByUserAndCode($this->input['user_id'], $this->input['code']);
        
        // if(! $record)
        // {
        //     $record = UserSetting::create($this->input);
        // }
        // else
        // {
        //     $record->update(['value' => $this->input['value']]);
        // }
        
    }

} 