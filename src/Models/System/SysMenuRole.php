<?php

namespace MyDpo\Models\System;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Traits\Actionable;

class SysMenuRole extends Model {

    use Actionable;
    
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

    public static function doMenusroles($input, $record) {

        $record = [];

        if($input['roles'] && is_array($input['roles']))
        {
            foreach($input['roles'] as $i => $role)
            {
                $data = [
                    ...$role,
                    'platform' => !! $role['platform'] ? implode(',', $role['platform']): '',
                    'menu_id' => $input['menu_id'],
                ];

                $record[] = self::CreateOrUpdate($data);
            }
        }

        return $record;
    }

    public static function CreateOrUpdate($input) {

        $record = self::where('menu_id', $input['menu_id'])
            ->where('role_id', $input['role_id']);
        
        if($input['customer_id'])
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

    }

}