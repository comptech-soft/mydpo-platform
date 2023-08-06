<?php

namespace MyDpo\Traits;

trait Centralizatorcolumnable { 
    
    public static function doInsert($input, $record) {

        if( ! array_key_exists('props', $input) )
        {
            $input['props'] = NULL;
        }

        if($input['is_group'] == 1)
        {
            $input = [
                ...$input,
                // 'order_no' => $input['order_no'],
                'width' => NULL,
                'type' => NULL,
            ];

        }
        else
        {
            $input = [
                ...$input,
                // 'order_no' => $input['order_no'],
            ];
        }

        $record = self::create($input);

        return $record;
    }

    public static function doUpdate($input, $record) {

        if( ! array_key_exists('props', $input) )
        {
            $input['props'] = NULL;
        }

        $record->update($input);

        return $record;
    }

    public static function doDelete($input, $record) {

        self::where('group_id', $record->id)->delete();
        
        $record->delete();
        
        return $record;
        
    }
    
}