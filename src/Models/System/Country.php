<?php

namespace MyDpo\Models\System;

use Illuminate\Database\Eloquent\Model;
// use MyDpo\Helpers\Performers\Datatable\GetItems;
// use MyDpo\Helpers\Performers\Datatable\DoAction;
use MyDpo\Traits\Itemable;

class Country extends Model {

    use Itemable;
    
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

    // public static function getItems($input) {
    //     return (new GetItems($input, self::query()->withCount('regions'), __CLASS__))->Perform();
    // }

    // public static function doAction($action, $input) {
    //     return (new DoAction($action, $input, __CLASS__))->Perform();
    // }

    public static function GetQuery() {
        return self::query()->withCount('regions');
    }

}