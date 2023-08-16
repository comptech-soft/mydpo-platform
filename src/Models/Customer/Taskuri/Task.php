<?php

namespace MyDpo\Models\Customer\Taskuri;

use Illuminate\Database\Eloquent\Model;
// use MyDpo\Helpers\Performers\Datatable\GetItems;
// use MyDpo\Helpers\Performers\Datatable\DoAction;

use MyDpo\Traits\Itemable;

class Task extends Model {

    use Itemable;
    
    protected $table = 'customers-tasks';

    // public static function getItems($input) {
    //     return (new GetItems($input, self::query()->with(['orders.services.service.type', 'customer']), __CLASS__))->Perform();
    // }

}