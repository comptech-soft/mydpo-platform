<?php

namespace MyDpo\Models\Customer\Contracts;

use MyDpo\Models\Customer\Customer_base as Customer;

use MyDpo\Rules\CustomerContract\ContractNumber;
use MyDpo\Traits\Itemable;

class CustomerContract extends Contract {

    use Itemable;
    
    protected $appends = [
        'days_difference',
        // 'last_order',
    ];

    protected $with = [
        'customer'
    ];

    // public function getLastOrderAttribute() {
    //     return $this->orders->first();
    // }

    function orders() {
        return $this->hasMany(Order::class, 'contract_id')->orderBy('date_to', 'desc');
    }

    // public function customer() {
    //     return $this->belongsTo(Customer::class, 'customer_id')->select(['id', 'name', 'status'])->with(['mystatus']);
    // }
    
    // public static function getItems($input) {
    //     return (new GetItems($input, self::query()->with(['orders.services.service.type', 'customer']), __CLASS__))->Perform();
    // }

    // public static function GetQuery() {
    //     return self::query()->withCount(['orders']);
    // }

}