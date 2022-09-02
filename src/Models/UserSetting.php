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
        'type',
        'description',
        'code',
        'value',
        'width',
        'decimals',
        'suffix',
        'props',
        'file_original_name',
        'file_original_extension',
        'file_full_name',
        'file_mime_type',
        'file_upload_ip',
        'url',
        'value',
        'file_size',
        'file_width',
        'file_height',
        'created_by',
        'updated_by'
    ]; 

    public static function saveActiveCustomer($input) {
        return self::saveSetting([
            ...$input,
            'code' =>  $input['platform'] . '-active-customer',
            'value' => $input['customer_id'],
        ]);
    }

    public static function getByUserAndCode($user_id, $code) {
        return self::where('user_id', $user_id)->where('code', $code)->first();
    }

    public static function saveSetting($input) {
        return (new SaveSetting($input))
            ->SetSuccessMessage('Saved successfully!')
            ->Perform();
    }

}