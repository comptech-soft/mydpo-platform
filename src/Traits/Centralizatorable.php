<?php

namespace MyDpo\Traits;

trait Centralizatorable { 

    /**
     * Definirea coloanelor implicite
     */
    protected static $default_columns_definition = [
        'has_nr_crt_column' => [
            'type' =>  'NRCRT',
            'caption' => 'Număr#curent',
            'is_group' => 0,
            'group_id' => NULL,
            'order_no' => -100,
            'width' => 100,
        ],

        'has_visibility_column' => [
            'type' =>  'VISIBILITY',
            'caption' => 'Vizibilitate',
            'is_group' => 0,
            'group_id' => NULL,
            'order_no' => -90,
            'width' => 100,
        ],

        'has_status_column' => [
            'type' =>  'STATUS',
            'caption' => 'Status',
            'is_group' => 0,
            'group_id' => NULL,
            'order_no' => -80,
            'width' => 100,
        ],

        'has_files_column' => [
            'type' =>  'FILES',
            'caption' => 'Fișiere',
            'is_group' => 0,
            'group_id' => NULL,
            'order_no' => -70,
            'width' => 100,
        ],

        'has_department_column' => [
            'type' =>  'DEPARTMENT',
            'caption' => 'Departament',
            'is_group' => 0,
            'group_id' => NULL,
            'order_no' => -60,
            'width' => 200,
        ],

        'has_empty_column' => [
            'type' =>  'EMPTY',
            'caption' => '',
            'is_group' => 0,
            'group_id' => NULL,
            'order_no' => 32767,
            'width' => 10,
        ],
    ];

    /**
     * Coloanela care au flag in tabela de tipuri de centralizatoare
     */
    protected static $in_table_columns = [
        'has_nr_crt_column',
        'has_visibility_column',
        'has_status_column',
        'has_files_column',
        'has_department_column'
    ];

    // public function DeleteColumnStatus() {
    //     $this->DeleteColumn('STATUS');
    // }

    // public function DeleteColumnDepartament() {
    //     $this->DeleteColumn('DEPARTMENT');
    // }

    // public function DeleteColumnVizibilitate() {
    //     $this->DeleteColumn('VISIBILITY');
    // }

    public function DeleteColumn($input) {

        $model = new ($this->columnsDefinition['model']);
        
        $record = $model->where($this->columnsDefinition['foreign_key'], $this->id)->whereType($input['type'])->first();

        if(!! $record)
        {
            return $record->delete();
        }

        return $record;
    }

    public function AddColumn($input) {
        
        $model = new ($this->columnsDefinition['model']);

        $record = $model->where($this->columnsDefinition['foreign_key'], $this->id)->whereType($input['type'])->first();

        $input = [
            $this->columnsDefinition['foreign_key'] => $this->id,
           ...$input,
        ];

        if(!! $record)
        {
            $record->update($input);
        }
        else
        {
            $record = $model->create($input);
        }

        return $record;

    }

    public static function doInsert($input, $record) {

        $record = self::create($input);

        foreach(self::$default_columns_definition as $field => $column)
        {

            if( array_key_exists($field, $input) && $input[$field] == 1)
            {
                $col = $record->AddColumn($column);
                	
                if( in_array($field, self::$in_table_columns))
                {
                    $record->{$field} = $col->id;
                    $record->save();
                }
            }
        }
        
        return self::withCount('columns')->find($record->id);
    }

    public static function doUpdate($input, $record) {
        
        $record->update($input);

        foreach(self::$default_columns_definition as $field => $column)
        {

            if( array_key_exists($field, $input) && $input[$field] == 1)
            {
                $col = $record->AddColumn($column);
                	
                if( in_array($field, self::$in_table_columns))
                {
                    $record->{$field} = $col->id;
                    $record->save();
                }
            }
            else
            {

                $record->DeleteColumn($column);

                if( in_array($field, self::$in_table_columns))
                {
                    $record->{$field} = NULL;
                    $record->save();
                }
            }
        }

        
        if(array_key_exists('body', $input))
        {
            foreach($input['body'] as $key => $value)
            {
                if($value == 1)
                {
                    $record->{'AddColumn' . ucfirst($key)}();
                }
                else
                {
                    $record->{'DeleteColumn' . ucfirst($key)}();
                }
            }
        }

        return self::withCount('columns')->find($record->id);
    }

    public static function doDelete($input, $record) {

        // CentralizatorColoana::where('centralizator_id', $record->id)->delete();

        $record->deleted = 1;
        $record->deleted_by = \Auth::user()->id;
        $record->name = $record->id . '#' . $record->name;
        $record->save();

        return $record;

    }
    
}