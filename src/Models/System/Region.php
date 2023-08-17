<?php

namespace MyDpo\Models\System;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;

class Region extends Model {
  
    use Itemable, Actionable;
    
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

    public static function GetQuery() {
        return self::query()->select(['id', 'name', 'code', 'country_id'])->withCount('cities');
    }

}