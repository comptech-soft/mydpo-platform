<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;

class CursType extends Model {
   
    protected $table = 'cursuri-types';

    protected $casts = [
        'id' => 'integer',
    ];

    protected $fillable = [
        'id',
        'name',
        'slug',
        'props',
    ];

    public static function getItems($input, $type = NULL) {
        return (new GetItems($input, self::query(), __CLASS__))->Perform();
    }

}