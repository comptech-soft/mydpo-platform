<?php

namespace MyDpo\Models\System;

use Illuminate\Database\Eloquent\Model;
// use Kalnoy\Nestedset\NodeTrait;
// use MyDpo\Helpers\Performers\Datatable\DoAction;
// use MyDpo\Traits\Itemable;
// use MyDpo\Models\Region;

class SysMenuRole extends Model {

    // use NodeTrait, Itemable;
    
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


}