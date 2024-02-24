<?php

namespace MyDpo\Models\Customer\Contracts;

use MyDpo\Models\Customer\Customer_base as Customer;

use MyDpo\Rules\CustomerContract\ContractNumber;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;

class CustomerContract extends Contract {

    use Itemable, Actionable;
    
    protected $appends = [
        'days_difference',
    ];

    protected $with = [
        'customer'
    ];

    function orders() {
        return $this->hasMany(Order::class, 'contract_id')->orderBy('date_to', 'desc');
    }

    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id')->select(['id', 'name', 'status'])->with(['mystatus']);
    }
    
    public static function GetQuery() {
        return self::query()->withCount(['orders']);
    }

}