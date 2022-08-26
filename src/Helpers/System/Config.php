<?php

namespace MyDpo\Helpers\System;

use MyDpo\Performers\System\GetConfig;

class Config {

    public static function getConfig() {

        return 
            (new GetConfig(NULL))
            ->SetSuccessMessage(NULL)
            ->SetExceptionMessage([
                \Exception::class => NULL,
            ])
            ->Perform();
    }
    
}