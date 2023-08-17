<?php

namespace MyDpo\Models\System;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;

class Country extends Model {

    use Itemable, Actionable;
    
    protected $table = 'countries';

    protected $fillable = [
        'id',
        'name',
        'uuid',
        'code',
        'vat_prefix',
        'phone_prefix',
        'created_by',
        'updated_by'
    ]; 

    function regions() {
        return $this->hasMany(Region::class, 'country_id');
    }

    public static function GetQuery() {
        return self::query()->withCount('regions');
    }

}