<?php

namespace MyDpo\Traits\Customer\Centralizatoare;

trait Centralizatorable {

    public static function doInsert($input, $record) {

        dd('bam bam....');
        
        $tip = TipRegistru::find($input['register_id']);

       
        $record = self::create([
            ...$input,

            'columns_tree' => $tip->columns_tree,
            'columns_items' => $tip->columns_items,
            'columns_with_values' => $tip->columns_with_values,

            'nr_crt_column_id' => $tip->has_nr_crt_column,
            'visibility_column_id' => $tip->has_visibility_column,
            'status_column_id' => $tip->has_status_column,
            'department_column_id' => $tip->has_department_column,
            'files_column_id' => $tip->has_files_column,

            'current_columns' => $tip->columns->toArray(), 
        ]);

        return $record;
    }

}