<?php

namespace MyDpo\Traits;

// use MyDpo\Performers\Traits\DoAction;

trait Exportable { 

    public static function doExport($input, $record) {
       
        dd(__METHOD__);
        // return (new DoAction($action, $input, __CLASS__))->Perform();

    }
    
}