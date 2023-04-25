<?php

namespace MyDpo\Traits;

// use MyDpo\Actions\Items\Dataprovider;
// use Comptech\Helpers\Performers\Datatable\DoAction;

trait Reorderable { 

    public static function reorder($input) {

        dd($input);
        // return (new GetItems($input, self::query(), __CLASS__))->Perform();
    }

    // public static function getItems($input) {

    //     $query = method_exists(__CLASS__, 'GetQuery') ? self::GetQuery() : self::query();

    //     return (new Dataprovider($input, $query, __CLASS__))->Perform();
    // }

    // public static function doAction($action, $input) {
    //     return (new DoAction($action, $input, __CLASS__))->Perform();
    // }
    
}