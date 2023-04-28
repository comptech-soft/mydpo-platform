<?php

namespace MyDpo\Traits;

use MyDpo\Performers\Traits\DoAction;

trait Actionable { 

    public static function doAction($action, $input) {
        // $input['slug'] = \Str::slug($input['name']);
        
        return (new DoAction($action, $input, __CLASS__))->Perform();
    }
    
}