<?php

namespace MyDpo\Models\System;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
// use MyDpo\Helpers\Performers\Datatable\GetItems;
// use MyDpo\Helpers\Performers\Datatable\DoAction;
// use MyDpo\Traits\Itemable;
// use MyDpo\Models\Region;

class SysRoute extends Model {

    use NodeTrait;
    
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

    // public function region() {
    //     return $this->belongsTo(Region::class, 'region_id');
    // }

    // public static function getItems($input) {
    //     return (new GetItems($input, self::query(), __CLASS__))->Perform();
    // }

    // public static function doAction($action, $input) {
    //     return (new DoAction($action, $input, __CLASS__))->Perform();
    // }
    
}