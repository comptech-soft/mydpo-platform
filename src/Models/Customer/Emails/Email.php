<?php

namespace MyDpo\Models\Customer\Emails;

use Illuminate\Database\Eloquent\Model;
// use MyDpo\Helpers\Performers\Datatable\GetItems;
// use MyDpo\Helpers\Performers\Datatable\DoAction;

use MyDpo\Traits\Itemable;

class Email extends Model {

    use Itemable;
    
    protected $table = 'customers-emails';

    // public static function getItems($input) {
    //     return (new GetItems($input, self::query()->with(['orders.services.service.type', 'customer']), __CLASS__))->Perform();
    // }

}