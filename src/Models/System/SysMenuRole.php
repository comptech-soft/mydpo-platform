<?php

namespace MyDpo\Models\System;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Traits\Actionable;
use MyDpo\Traits\Itemable;

class SysMenuRole extends Model {

    use Actionable, Itemable;
    
    protected $table = 'system-menus-roles';

    protected $fillable = [
        'id',
        'menu_id',
        'role_id',
        'customer_id',
        'platform',
        'visible',
        'disabled',
    ];

    protected $casts = [
        'id' => 'integer',
        'menu_id' => 'integer',
        'role_id' => 'integer',
        'customer_id' => 'integer',
        'visible' => 'integer',
        'disabled' => 'integer',
    ];

    public static function CreateOrUpdate($input) {

        $record = self::where('menu_id', $input['menu_id'])
            ->where('role_id', $input['role_id'])
            ->where('platform', $input['platform']);
        
        if(array_key_exists('customer_id', $input) && $input['customer_id'])
        {
            $record = $record->where('customer_id', $input['customer_id']);
        }
        
        $record = $record->first();

        if($record)
        {
            $record->update($input);
        }
        else
        {
            $record = self::create($input);
        }

        return $record;

    }

}