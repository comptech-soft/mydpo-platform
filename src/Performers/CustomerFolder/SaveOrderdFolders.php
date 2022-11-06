<?php

namespace MyDpo\Performers\CustomerFolder;

use MyDpo\Helpers\Perform;
use MyDpo\Models\UserSetting;

class SaveOrderdFolders extends Perform {

    public function Action() {

        dd($this->input);

        UserSetting::saveSetting([
            'user_id' => $this->input['user_id'], 
            'platform' => $this->input['platform'],
            'customer_id' => $this->input['customer_id'], 
            'code' =>  $this->input['platform'] . '-' . $this->input['customer_id'] . '-customer-folders-order',
            'value' => $this->input['items'],
        ]);  
    
    }
}