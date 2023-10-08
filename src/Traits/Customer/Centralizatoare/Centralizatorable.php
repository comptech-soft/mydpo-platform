<?php

namespace MyDpo\Traits\Customer\Centralizatoare;

use MyDpo\Models\Customer\Departments\Department;
use MyDpo\Models\Customer\Centralizatoare\Centralizator;

trait Centralizatorable {

    /**
     * Atributul visible pentru interfata
     */
    public function getVisibleAttribute() {
        return [
            'color' => !! $this->visibility ? 'green' : 'red',
            'icon' => !! $this->visibility ? 'mdi-check' : 'mdi-cancel',
        ];
    }

    /**
     * Atributul Headerul pentru tabel
     */
    public function getTableHeadersAttribute() {
        return $this->columns_tree;
    }
    
    /**
     * Relatie catre departament
     */
    public function department() {
        return $this->belongsTo(Department::class, 'department_id')->select(['id', 'departament']);
    }

    /**
     * Crearea unui document
     */
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

    /**
     * Duplicarea unui element
     */
    public static function doDuplicate($input, $record) {

        return self::Duplicate($input);

    }

    /**
     * Stergerea unui document
     */
    public static function doDelete($input, $record) {

        $record->DeleteRows();
        $record->delete();
        
        return $record;
    }
    
    /**
     * Generarea inregistrarilor
     */
    public static function GetQuery() {

        $q = self::query();

        if(config('app.platform') == 'b2b')
        {
            $q = $q->where('visibility', 1);
        }
        
        return $q;
    }

}