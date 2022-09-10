<?php

namespace MyDpo\Models;

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

    public static function getByUserAndCustomerAndCodeAndPlatform($user_id, $customer_id, $code, $platform) {
        return self::where('user_id', $user_id)
            ->where('code', $code)
            ->where('customer_id', $customer_id)
            ->where('platform', $platform)
            ->first();
    }

    public static function saveActiveCustomer($input) {
        return self::saveSetting([
            ...$input,
            'code' =>  $input['platform'] . '-active-customer',
            'value' => $input['customer_id'],
        ]);
    }

    public static function saveSetting($input) {
        return (new SaveSetting($input))
            ->SetSuccessMessage('Saved successfully!')
            ->Perform();
    }

}