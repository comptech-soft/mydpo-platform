<?php

namespace MyDpo\Performers\CustomerDashboardItem;

use MyDpo\Helpers\Perform;
use MyDpo\Models\UserSetting;

class SaveReorderedItems extends Perform {

    public static function MakeSettingCode($platform, $customer_id, $user_id) {
        return  $platform . '-customer-dashboard-' . $customer_id . '-' . $user_id;
    }

    public function Action() {

        $code = self::MakeSettingCode(
            $this->input['platform'],
            $this->input['customer_id'],
            $this->input['user_id']
        );
        
        UserSetting::saveSetting([
            'user_id' => $this->input['user_id'], 
            'code' => $code ,
            'value' => $this->input['items'],
        ]);  
    }
}