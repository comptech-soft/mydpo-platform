<?php

namespace MyDpo\Performers\CustomerFolder;

use MyDpo\Helpers\Perform;
use MyDpo\Models\UserSetting;

class SaveOrderdFolders extends Perform {

    public function Action() {

        if($this->input['type'] == 'documente')
        {
            $code = $this->input['platform'] . '-' . $this->input['customer_id'] . '-customer-folders-order';
        }
        else
        {
            $code = $this->input['platform'] . '-' . $this->input['customer_id'] . '-customer-' . $this->input['type'] . '-order';
        }

        UserSetting::saveSetting([
            'user_id' => $this->input['user_id'], 
            'platform' => $this->input['platform'],
            'customer_id' => $this->input['customer_id'], 
            'code' =>  $code,
            'value' => $this->input['items'],
        ]);  
    
    }
}