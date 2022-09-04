<?php

namespace MyDpo\Traits;

use MyDpo\Performers\Traits\GetNextNumber;

trait NextNumber { 

    public static function getNextNumber($input) {

        return (new GetNextNumber([
            ...$input, 
            'model' => __CLASS__
        ]))
            ->SetSuccessMessage(NULL)
            ->Perform();
        
    }
}