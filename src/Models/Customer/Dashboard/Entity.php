<?php

namespace MyDpo\Models\Customer\Dasboard;

use Illuminate\Database\Eloquent\Model;

class Entity extends Model {

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

        foreach(self::orderBy('order_no')->get() as $i => $record)
        {
            if( in_array(config('app.platform'), $record->platform) )
            {
                $r[] = $record;
            }
        }

        return $r;
    }

}