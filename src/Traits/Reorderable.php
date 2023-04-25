<?php

namespace MyDpo\Traits;

use MyDpo\Performers\Traits\Reorder;

trait Reorderable { 

    public static function reorder($input) {

        return (new Reorder([
            ...$input, 
            'table' => (new (__CLASS__)())->getTable(),
        ]))
            ->SetSuccessMessage('Înregistrările au fost reordonate cu succes.')
            ->Perform();
    }

    // public static function getItems($input) {

    //     $query = method_exists(__CLASS__, 'GetQuery') ? self::GetQuery() : self::query();

    //     return (new Dataprovider($input, $query, __CLASS__))->Perform();
    // }

    // public static function doAction($action, $input) {
    //     return (new DoAction($action, $input, __CLASS__))->Perform();
    // }
    
}