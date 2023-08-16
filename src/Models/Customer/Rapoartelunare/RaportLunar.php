<?php

namespace MyDpo\Models\Customer\Rapoartelunare;

use Illuminate\Database\Eloquent\Model;
// use MyDpo\Helpers\Performers\Datatable\GetItems;
// use MyDpo\Helpers\Performers\Datatable\DoAction;

use MyDpo\Traits\Itemable;

class RaportLunar extends Model {

    use Itemable;
    
    protected $table = 'customers-rapoarte-lunare';

    // public static function getItems($input) {
    //     return (new GetItems($input, self::query()->with(['orders.services.service.type', 'customer']), __CLASS__))->Perform();
    // }

}