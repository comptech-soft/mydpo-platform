<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use MyDpo\Models\Customer;

class CustomerContract extends Model {

    protected $table = 'customers-contracts';

    protected $casts = [
        'props' => 'json',
        'prelungire_automata' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
        'deleted' => 'integer',
        'customer_id' => 'integer',
        'date_to_history' => 'json',
    ];

    protected $fillable = [
        'id',
        'number',
        'date_from',
        'date_to',
        'customer_id',
        'prelungire_automata',
        'deleted',
        'status',
        'props',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $appends = [
        'days_difference',
    ];

    /**
     * Diferenta de azi pana la date_to
     * < 0 ==> OK
     * = 0 ==> expira azi
     * > 0 ==> a expirat
     */
    public function getDaysDifferenceAttribute() {   
        $now = Carbon::now();
        $expire = Carbon::createFromFormat('Y-m-d', $this->date_to);
        
        $daysDiff = $now->diffInDays($expire);
        $hoursDiff = $now->diffInHours($expire);

        $color = 'green';
        if($daysDiff > 0)
        {
            $color = 'red';
        }
        else
        {
            if($daysDiff == 0)
            {
                $color = 'orange';
                if($hoursDiff > 0)
                {
                    $color = 'red';
                }
            }
        }

        return [
            'now' => $now->format('Y-m-d'),
            'date_to' => $this->date_to,
            'days' => $daysDiff,
            'hours' => $hoursDiff,
            'color' => $color,
        ];
    }

    // function orders() {
    //     return $this->hasMany(CustomerOrder::class, 'contract_id')->orderBy('date', 'desc');
    // }
    
    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

}