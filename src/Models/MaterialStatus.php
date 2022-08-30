<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;

class MaterialStatus extends Model {

    protected $table = 'materiale-statuses';

    protected $casts = [
        'applied_to' => 'json',
    ];

    protected $fillable = [
        'id',
        'name',
        'slug',
        'applied_to',
    ];

    public static function getItems($input) {
        return (new GetItems($input, self::query(), __CLASS__))->Perform();
    }


}