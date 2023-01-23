<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Performers\Translation\CreateKey;
use MyDpo\Helpers\Performers\Datatable\GetItems;    

class Translation extends Model 
{

    protected $table = 'translations';

    protected $fillable = [
        'id',
        'ro',
        'en',      
        'created_by',
        'updated_by',
    ];

    public static function getItems($input) {
        return (new GetItems($input, self::query(), __CLASS__))->Perform();
    }

    public static function createKey($input) {
        return (new CreateKey($input))
            ->Perform();
    }

}