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
        dd($input);
    }

}