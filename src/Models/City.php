<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;
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
}