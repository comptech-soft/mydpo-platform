<?php

namespace MyDpo\Traits;

// use Comptech\Helpers\Performers\Datatable\GetItems;
// use Comptech\Helpers\Performers\Datatable\DoAction;

trait Itemable { 

    public static function getItems($input) {

        dd('11111');

        $query = method_exists(__CLASS__, 'GetQuery') ? self::GetQuery() : self::query();

        return (new GetItems($input, $query, __CLASS__))->Perform();
    }

    // public static function doAction($action, $input) {
    //     return (new DoAction($action, $input, __CLASS__))->Perform();
    // }
    
}