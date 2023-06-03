<?php

namespace MyDpo\Models\System;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
// use MyDpo\Helpers\Performers\Datatable\GetItems;
// use MyDpo\Helpers\Performers\Datatable\DoAction;
use MyDpo\Traits\Itemable;
// use MyDpo\Models\Region;

class SysRoute extends Model {

    use NodeTrait, Itemable;
    
    protected $table = 'system-routes';

    protected $fillable = [
        'id',
        'slug',
        'type',
        'platform',
        'order_no',
        'name',
        'prefix',
        'middleware',
        'path',
        'verb',
        'controller',
        'method',
        'description',
        'props',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'id' => 'integer',
        'middleware' => 'json',
        'platform' => 'json',
        'props' => 'json',
        'order_no' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

}