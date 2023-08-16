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
        'departments' => \MyDpo\Models\Customer\Departments\Department::class,
        'contracts' => \MyDpo\Models\Customer\Contracts\Contract::class,
        'orders' => \MyDpo\Models\Customer\Contracts\Order::class,
        'services' => \MyDpo\Models\Customer\Contracts\OrderService::class,
        'accounts' => \MyDpo\Models\Customer\Accounts\Account::class,
        'team' => \MyDpo\Models\Customer\Teams\Team::class,
        'monthly-reports' => \MyDpo\Models\Customer\Rapoartelunare\RaportLunar::class,
        'tasks' => \MyDpo\Models\Customer\Taskuri\Task::class,
        'emails' => \MyDpo\Models\Customer\Emails\Email::class,
        'notifications' => \MyDpo\Models\Customer\Notifications\Notification::class,
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
                $count = array_key_exists($record->slug, self::$myclasses) 
                    ? self::$myclasses[$record->slug]::where('customer_id', request()->customer_id)->count()
                    : -1;
                

                $r[] = [
                    ...$record->toArray(),
                    'count' => $count,
                ];
            }
        }
        
        return $r;
    }

}