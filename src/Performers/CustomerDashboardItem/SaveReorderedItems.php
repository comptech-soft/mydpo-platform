<?php

namespace MyDpo\Performers\CustomerDashboardItem;

use MyDpo\Helpers\Perform;
use MyDpo\Models\UserSetting;

class SaveReorderedItems extends Perform {

    public function Action() {

        dd($this->input);

        $code = 'customer-dashboard-' . $this->input['platform'] . ($this->input['customer_id'] ? '-' . $this->input['customer_id'] : '');
        
        UserSetting::saveSetting([
            'user_id' => $this->input['user_id'], 
            'code' => $code ,
            'value' => $this->input['lists'],
        ]);  
    }
}