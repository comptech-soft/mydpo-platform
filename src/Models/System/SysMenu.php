<?php

namespace MyDpo\Models\System;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
// use MyDpo\Helpers\Performers\Datatable\DoAction;
use MyDpo\Traits\Itemable;
// use MyDpo\Models\Region;

class SysMenu extends Model {

    use NodeTrait, Itemable;
    
    protected $table = 'system-menus';

    protected $fillable = [
        'id',
        'slug',
        'type',
        'platform',
        'order_no',
        'caption',
        'icon',
        'description',
        'props',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'id' => 'integer',
        'platform' => 'json',
        'props' => 'json',
        'order_no' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    protected $with = [
        'children',
    ];

    public static function GetBySlug($slug) {

        return self::whereSlug($slug)->first();
    }

}