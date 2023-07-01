<?php

namespace MyDpo\Models\Customer;

use Illuminate\Database\Eloquent\Model;

class CustomerEntityItem extends Model {

    protected $table = 'customers-entities-items';

    protected $casts = [
        'props' => 'json',
        'platform' => 'json',
    ];
    
    protected $fillable = [
        'id',
        'title',
        'slug',
        'platform',
        'image',
        'order_no',
        'props',
    ];
   
   
    public static function getByPlatform() {

        $r = [];

        foreach(self::all() as $i => $record)
        {
            if( in_array(config('app.platform'), $record->platform) )
            {
                $r[] = $record;
            }
        }

        return $r;
    }

}