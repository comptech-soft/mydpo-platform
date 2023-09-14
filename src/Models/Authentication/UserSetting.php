<?php

namespace MyDpo\Models\Authentication;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Performers\UserSetting\SaveSetting;

class UserSetting extends Model {
    
    protected $table = 'users-settings';

    protected $casts = [
        'props' => 'json',
        'value' => 'json',
    ];

    protected $fillable = [
        'id',
        'user_id',
        'customer_id',
        'platform',
        'type',
        'description',
        'code',
        'value',
        'created_by',
        'updated_by'
    ]; 

    /**
     * Returneaza customer-ul default pentru un anumit $user
     */
    public static function GetDefaultCustomer($platform, $user) {
        
        $record = self::wherePlatform($platform)->where('user_id', $user->id)->whereCode('b2b-active-customer')->first();

        dd($record);
        
        // $settings = $user->settings()->where('code', )->first())
    }


    public static function getByUserAndCustomerAndCodeAndPlatform($user_id, $customer_id, $code, $platform) {
        return self::where('user_id', $user_id)
            ->where('code', $code)
            ->where('customer_id', $customer_id)
            ->where('platform', $platform)
            ->first();
    }

    public static function saveActiveCustomer($input) {
        return self::saveSetting([
            'user_id' => $input['user_id'],
            'platform' => $input['platform'],
            'customer_id' => NULL,
            'code' =>  $input['platform'] . '-active-customer',
            'value' => $input['customer_id'],
        ]);
    }

    public static function saveSetting($input) {
        return (new SaveSetting($input))
            ->Perform();
    }

}