<?php

namespace MyDpo\Models\System;

use Illuminate\Database\Eloquent\Model;
// use MyDpo\Helpers\Performers\Datatable\GetItems;
// use MyDpo\Helpers\Performers\Datatable\DoAction;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;

class City extends Model {

    use Itemable, Actionable;
    
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

    public static function GetQuery() {
        return self::query()->select(['id', 'name', 'postal_code', 'region_id']);
    }
    
}