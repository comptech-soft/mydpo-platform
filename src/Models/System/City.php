<?php

namespace MyDpo\Models\System;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Helpers\Performers\Datatable\DoAction;
use MyDpo\Traits\Itemable;
use MyDpo\Models\Region;

class City extends Model {

    use Itemable;
    
    protected $table = 'cities';

    protected $fillable = [
        'id',
        'uuid',
        'name',
        'postal_code',
        'siruta',
        'latitude',
        'longitude',
        'region_id',
        'created_by',
        'updated_by'
    ];

    public function region() {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public static function getItems($input) {
        return (new GetItems($input, self::query(), __CLASS__))->Perform();
    }

    public static function doAction($action, $input) {
        return (new DoAction($action, $input, __CLASS__))->Perform();
    }
    
}