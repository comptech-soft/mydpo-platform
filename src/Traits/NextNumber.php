<?php

namespace MyDpo\Traits;

use MyDpo\Performers\Traits\GetNextNumber;

trait NextNumber { 

    public static function getNextNumber($input) {

        return (new GetNextNumber([
            ...$input, 
            'instance' => new static
        ]))
            ->SetSuccessMessage('Schimbare status cu success!')
            ->Perform();

       
        
    }
}