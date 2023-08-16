<?php

namespace MyDpo\Performers\CustomerDashboardItem;

use MyDpo\Helpers\Perform;
use MyDpo\Models\Authentication\UserSetting;

class SaveReorderedItems extends Perform {

    public function Action() {       
        UserSetting::saveSetting([
            'user_id' => $this->input['user_id'], 
            'platform' => $this->input['platform'],
            'customer_id' => $this->input['customer_id'], 
            'code' =>  $this->input['platform'] . '-' . $this->input['customer_id'] . '-customer-dashboard',
            'value' => $this->input['items'],
        ]);  
    }
}