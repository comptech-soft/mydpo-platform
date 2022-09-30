<?php

namespace MyDpo\Helpers\System;

use MyDpo\Performers\System\GetConfig;
use MyDpo\Performers\System\GetUserRole;

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
    
    public static function getUserRole($input) {

        return 
            (new GetUserRole($input))
            ->SetSuccessMessage(NULL)
            ->SetExceptionMessage([
                \Exception::class => NULL,
            ])
            ->Perform();
    }
    
}