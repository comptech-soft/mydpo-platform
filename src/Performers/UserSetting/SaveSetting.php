<?php

namespace MyDpo\Performers\UserSetting;

use MyDpo\Helpers\Perform;
use MyDpo\Models\UserSetting;

class SaveSetting extends Perform {

    public function Action() {

        dd($this->input);

        $record = UserSetting::getByUserAndCustomerAndCode(
            $this->input['user_id'], 
            $this->input['customer_id'], 
            $this->input['code']
        );
        
        if(! $record)
        {
            $record = UserSetting::create($this->input);
        }
        else
        {
            $record->update(['value' => $this->input['value']]);
        }
        
    }

} 