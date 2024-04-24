<?php

namespace MyDpo\Traits;

use MyDpo\Actions\Items\Dataprovider;

trait Itemable { 

    public static function getRecords($input) {
        $query = method_exists(__CLASS__, 'GetQuery') ? self::GetQuery() : self::query();
        $input = method_exists(__CLASS__, 'PrepareQueryInput') ? self::PrepareQueryInput($input) : $input;
        return (new Dataprovider($input, $query, __CLASS__))->Perform();
    }
        
}