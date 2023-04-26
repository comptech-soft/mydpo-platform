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
    
}