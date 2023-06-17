<?php

namespace MyDpo\Traits;

use MyDpo\Performers\Traits\DoAction;

trait Actionable { 

    public static function doAction($action, $input) {
       
        return (new DoAction($action, $input, __CLASS__))->Perform();

    }
    
}