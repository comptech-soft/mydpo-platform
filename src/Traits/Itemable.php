<?php

namespace MyDpo\Traits;

use MyDpo\Actions\Items\Dataprovider;
// use Comptech\Helpers\Performers\Datatable\DoAction;

trait Itemable { 

    public static function getItems($input) {

        $query = method_exists(__CLASS__, 'GetQuery') ? self::GetQuery() : self::query();

        return (new Dataprovider($input, $query, __CLASS__))->Perform();
    }
        
}