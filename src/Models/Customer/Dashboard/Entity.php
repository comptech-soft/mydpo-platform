<?php

namespace MyDpo\Models\Customer\Dashboard;

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

    protected static $myclasses = [
        'department' => \MyDpo\Models\Customer\Departments\Department::class,
    ];
   
    /**
     * Returneza entitatile care se afiseaza pe dashboard-ul clientului
     * in functie de ce este in campul `customers-entities-items`.`platform`
     */
    public static function GetByPlatform() {

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