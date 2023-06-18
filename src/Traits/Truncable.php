<?php

namespace MyDpo\Traits;

trait Truncable { 

    public static function doTruncate($input, $record) {
       
        self::query()->delete();

        return TRUE;
    }
    
}