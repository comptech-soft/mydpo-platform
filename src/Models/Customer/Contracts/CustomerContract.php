<?php

namespace MyDpo\Models\Customer\Contracts;

// use MyDpo\Helpers\Performers\Datatable\GetItems;
// use MyDpo\Helpers\Performers\Datatable\DoAction;

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
        return $this->hasMany(CustomerOrder::class, 'contract_id')->orderBy('date_to', 'desc');
    }

    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id')->select(['id', 'name', 'status'])->with(['mystatus']);
    }
    
    // public static function getItems($input) {
    //     return (new GetItems($input, self::query()->with(['orders.services.service.type', 'customer']), __CLASS__))->Perform();
    // }

}