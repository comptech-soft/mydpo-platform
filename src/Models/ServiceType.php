<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Models\Service;

class ServiceType extends Model {

    protected $table = 'services-types';

    protected $casts = [
        'props' => 'json',
    ];

    protected $fillable = [
        'id',
        'name',
        'slug',
        'color',
        'props',
    ];

    function services() {
        return $this->hasMany(Service::class, 'type');
    }

    public static function getItems($input) {
        return (new GetItems($input, self::query()->with(['services']), __CLASS__))->Perform();
    }

}