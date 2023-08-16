<?php

namespace MyDpo\Models\Customer;

use MyDpo\Models\Customer_base;

class Customer extends Customer_base {

    protected $with = [
        'mystatus', 
        'city.region.country', 
    ];

    protected $appends = [
        'full_city',
        'region_id',
        'country_id',
        'last_contract',
        'mylogo',
    ];

}