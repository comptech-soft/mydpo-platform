<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Performers\Translation\CreateKey;

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

    public static function createKey($input) {
        return (new CreateKey($input))
            ->Perform();
    }

}