<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Models\Region;

class Country extends Model {

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

    public static function getItems($input) {
        return (new GetItems($input, self::query()->withCount('regions'), __CLASS__))->Perform();
    }

    

}