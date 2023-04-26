<?php

namespace MyDpo\Traits;

use MyDpo\Performers\Traits\Reorder;

trait Actionable { 

    public static function doAction($action, $input) {
        $input['slug'] = \Str::slug($input['name']);
        
        dd($action, $input);
        
        // return (new DoAction($action, $input, __CLASS__))->Perform();
    }
    
}