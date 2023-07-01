<?php

namespace MyDpo\Models\Customer;

use Illuminate\Database\Eloquent\Model;

class CustomerEntityItem extends Model {

    protected $table = 'customers-entities-items';

    protected $casts = [
        'props' => 'json',
    ];
    
    protected $fillable = [
        'id',
        'name',
        'slug',
        'icon',
        'image',
        'title',
        'slot',
        'order_no',
        'visible_on_admin',
        'visible_on_b2b',
        'props',
    ];
   
    public static function getByPlatform() {

        $r = [];

        

        return $r;
    }

}