<?php

namespace MyDpo\Performers\UserSetting;

use MyDpo\Helpers\Perform;
use MyDpo\Models\UserSetting;

class SaveSetting extends Perform {

    public function Action() {

        $record = UserSetting::getByUserAndCode($this->input['user_id'], $this->input['code']);
        
        if(! $record)
        {
            $record = UserSetting::create($this->input);
            dd($record);
        }
        else
        {
            $record->update(['value' => $this->input['value']]);
        }
        
    }

} 