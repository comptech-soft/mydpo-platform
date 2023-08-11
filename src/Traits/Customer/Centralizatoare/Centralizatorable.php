<?php

namespace MyDpo\Traits\Customer\Centralizatoare;

use MyDpo\Models\Customer\CustomerDepartment;

trait Centralizatorable {

    public static function doInsert($input, $record) {
        
        $tip = self::GetTip($input);
       
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

    public function getVisibleAttribute() {
        return [
            'color' => !! $this->visibility ? 'green' : 'red',
            'icon' => !! $this->visibility ? 'mdi-check' : 'mdi-cancel',
        ];
    }

    public function getTableHeadersAttribute() {
        return $this->columns_tree;
    }
    
    public function department() {
        return $this->belongsTo(CustomerDepartment::class, 'department_id')->select(['id', 'departament']);
    }

    public static function GetQuery() {

        $q = self::query();

        if(config('app.platform') == 'b2b')
        {
            $q = $q->where('visibility', 1);
        }
        
        return $q;
    }

}