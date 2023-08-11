<?php

namespace MyDpo\Traits\Customer\Centralizatoare;

trait Rowable {

    public static function doInsert($input, $record) {

        $row = self::create([
            ...$input,
            'props' => [
                'rowvalues' => $input['rowvalues'],
            ] 
        ]);


        dd($input);
        
        foreach($input['rowvalues'] as $i => $rowvalue)
        {
            $rowvalue['row_id'] = $row->id;

            $rowvalue['column'] = $rowvalue['type'];

            CustomerCentralizatorRowValue::create($input);
        }

        dd(11);
    }
}