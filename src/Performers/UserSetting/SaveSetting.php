<?php

namespace MyDpo\Performers\UserSetting;

use MyDpo\Helpers\Perform;
use MyDpo\Models\Authentication\UserSetting;

class SaveSetting extends Perform {

    public function Action() {

        $record = UserSetting::getByUserAndCustomerAndCodeAndPlatform(
            $this->input['user_id'], 
            $this->input['customer_id'], 
            $this->input['code'],
            $this->input['platform']
        );
        
        if(! $record)
        {
            $record = UserSetting::create($this->input);
        }
        else
        {
            $record->update([
                'value' => $this->input['value']]);
        }
        
    }

} 