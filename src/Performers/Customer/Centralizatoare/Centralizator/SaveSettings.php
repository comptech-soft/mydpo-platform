<?php

namespace MyDpo\Performers\Customer\Centralizatoare\Centralizator;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerCentralizator;

class SaveSettings extends Perform {

    public function Action() {

        $record = CustomerCentralizator::find($this->id);

        $widths = collect($this->columns)->pluck('width', 'id')->toArray();

        $current_columns = $record->current_columns;
        $props = !! $record->props ? $record->props : [];

        foreach($widths as $column_id => $width)
        {

            $ok = false;

            foreach($current_columns as $i => $column)
            {
                if($column_id == $column['id'])
                {
                    $ok = true;
                    $current_columns[$i]['width'] = $width;
                }
            }

            if( ! $ok )
            {
                $props[$column_id] = $width;
            }
        }

        $record->current_columns = $current_columns;
        $record->props = $props;

        $record->save();

        $this->payload = [
            'record' => $record,
        ];
    
    }
}