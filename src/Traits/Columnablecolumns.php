<?php

namespace MyDpo\Traits;

trait Columnablecolumns { 
    
    public static function doInsert($input, $record) {

        $input = [
            ...$input,
            'slug' => md5(time()),
        ];

        if( ! array_key_exists('props', $input) )
        {
            $input['props'] = NULL;
        }

        if($input['is_group'] == 1)
        {
            $input = [
                ...$input,
                'order_no' => $input['order_no'],
                'width' => NULL,
                'type' => NULL,
            ];

        }
        else
        {
            $input = [
                ...$input,
                'order_no' => $input['order_no'],
            ];
        }

        $record = self::create($input);

        return $record;
    }
    
}