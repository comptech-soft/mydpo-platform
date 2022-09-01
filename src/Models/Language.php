<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model 
{

    protected $table = 'languages';

    protected $casts = [
        'props' => 'json',
        'number_format' => 'json',
        'date_format' => 'json',
        'datetime_format' => 'json',
        'id' => 'integer',
        'order_no' => 'integer',
    ];

    protected $fillable = [
        'id',
        'slug',
        'name',              
        'flag_icon',
        'currency',
        'number_format',
        'date_format',
        'datetime_format',
        'props',
        'order_no'
    ];

    public static function getBySlug() {

        $result = [];
        
        foreach(self::all() as $language)
        {
            $result[$language->slug] = $language;
        }

        return $result;
    }


}