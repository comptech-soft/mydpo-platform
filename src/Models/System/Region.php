<?php

namespace MyDpo\Models\System;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Helpers\Performers\Datatable\DoAction;
use MyDpo\Traits\Itemable;
use MyDpo\Models\Country;
use MyDpo\Models\City;

class Region extends Model {
  
    use Itemable;
    
    protected $table = 'regions';

    protected $fillable = [
        'id',
        'uuid',
        'name',
        'code',
        'nuts',
        'country_id',
        'created_by',
        'updated_by'
    ];

    public function country() {
        return $this->belongsTo(Country::class, 'country_id');
    }

    function cities() {
        return $this->hasMany(City::class, 'region_id');
    }

    public static function getItems($input) {
        return (new GetItems($input, self::query()->withCount(['cities']), __CLASS__))->Perform();
    }

    public static function doAction($action, $input) {
        return (new DoAction($action, $input, __CLASS__))->Perform();
    }

    public static function GetQuery() {
        return self::query()->withCount('cities');
    }

}