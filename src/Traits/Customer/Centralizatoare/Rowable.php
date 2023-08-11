<?php

namespace MyDpo\Traits\Customer\Centralizatoare;

use MyDpo\Models\Customer\Centralizatoare\RowValue as CentralizatorRowValue;

trait Rowable {

    public static function doInsert($input, $record) {

        $row = self::create([
            ...$input,
            'props' => [
                'rowvalues' => $input['rowvalues'],
            ] 
        ]);

        foreach($input['rowvalues'] as $i => $rowvalue)
        {
            $rowvalue['row_id'] = $row->id;

            $rowvalue['column'] = $rowvalue['type'];

            if($input['model'] == 'centralizatoare')
            {
                CentralizatorRowValue::create($rowvalue);
            }
        }

        return self::find($row->id);
    }
}