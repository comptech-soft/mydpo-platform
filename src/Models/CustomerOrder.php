<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Models\Customer;
use MyDpo\Models\CustomerContract;

use MyDpo\Traits\DaysDifference;

class CustomerOrder extends Model {
    
    use DaysDifference;

    protected $table = 'customers-orders';

    protected $casts = [
        'props' => 'json',
        'customer_id' => 'integer',
        'contract_id' => 'integer',
        'prelungire_automata' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'date_to_history' => 'json',
        'deleted' => 'integer',
    ];

    protected $fillable = [
        'id',
        'number',
        'date',
        'date_to',
        'customer_id',
        'contract_id',
        'prelungire_automata',
        'date_to_history',
        'deleted',
        'props',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $appends = [
        'days_difference',
    ];

    /** order->services */
    // function services() {
    //     return $this->hasMany(CustomerOrderService::class, 'order_id');
    // }
    
    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /** order->contract */
    public function contract() {
        return $this->belongsTo(CustomerContract::class, 'contract_id');
    }
    

}