<?php

namespace MyDpo\Traits\Admin\Livrabile\Tipuri;

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
            'width' => 55,
        ],

        'has_check_column' => [
            'type' =>  'CHECK',
            'caption' => 'Selectare',
            'is_group' => 0,
            'group_id' => NULL,
            'order_no' => -99,
            'width' => 50,
        ],

        'has_visibility_column' => [
            'type' =>  'VISIBILITY',
            'caption' => 'Vizibilitate',
            'is_group' => 0,
            'group_id' => NULL,
            'order_no' => -90,
            'width' => 50,
        ],

        'has_status_column' => [
            'type' =>  'STATUS',
            'caption' => 'Status',
            'is_group' => 0,
            'group_id' => NULL,
            'order_no' => -80,
            'width' => 140,
        ],

        'has_files_column' => [
            'type' =>  'FILES',
            'caption' => 'Fișiere',
            'is_group' => 0,
            'group_id' => NULL,
            'order_no' => -95,
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
            'width' => NULL,
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

    public function getBoolColNrcrtAttribute() {
        return !! $this->has_nr_crt_column ? 1 : 0;
    }

    public function getBoolColVisibilityAttribute() {
        return !! $this->has_visibility_column ? 1 : 0;
    }

    public function getBoolColStatusAttribute() {
        return !! $this->has_status_column ? 1 : 0;
    }

    public function getBoolColFilesAttribute() {
        return !! $this->has_files_column ? 1 : 0;
    }

    public function getBoolColDepartmentAttribute() {
        return !! $this->has_department_column ? 1 : 0;
    }

    public function getColumnsTreeAttribute() {
        $columns = collect($this->columns)
            ->filter(function($column){
                return ! $column['group_id'];
            })
            ->map(function($item) {
                $item = collect($item)->only(['id', 'order_no', 'is_group', 'group_id', 'caption', 'type', 'width', 'props'])->toArray();
                return [
                    ...$item,
                    'children' => [],
                ];
            })
            ->sortBy('order_no')
            ->values()
            ->toArray();

        foreach($columns as $i => $column)
        {
            $columns[$i]['children'] = self::CreateColumnChildren($column, $this->columns);
        }
        return $columns;
    }

    public function getColumnsItemsAttribute() {
        $list = [];
        foreach($this->columns_tree as $i => $node)
        {
            if( count($node['children']) == 0)
            {
                $list[] = $node;
            }

            foreach($node['children'] as $j => $child)
            {
                $list[] = [
                    ...$child,
                    'children' => [],
                ];
            }
        }
        return $list;
    }

    public function getColumnsWithValuesAttribute() {   
        $result = collect($this->columns_items)->filter( function($item) {
            return count($item['children']) == 0;
        });
        return $result->toArray();
    }

    private static function CreateColumnChildren($column, $current_columns) {

        $children = [];

        foreach($current_columns as $i => $item)
        {
            if(!! $item['group_id'] && ($item['group_id'] == $column['id']))
            {
                $children[] = $item;
            }
        }

        return collect($children)
            ->map(function($item) {
                $item = collect($item)->only(['id', 'order_no', 'is_group', 'group_id', 'caption', 'type', 'width', 'props'])->toArray();
                return $item;
            })
            ->sortBy('order_no')
            ->values()
            ->toArray();
    }

    /**
     * Stergere coloana
     */
    public function DeleteColumn($input) {

        $model = new ($this->columnsDefinition['model']);
        
        $record = $model->where($this->columnsDefinition['foreign_key'], $this->id)->whereType($input['type'])->first();

        if(!! $record)
        {
            return $record->delete();
        }

        return $record;
    }

    /**
     * Adaugare coloana
     */
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

    /**
     * Adaugare tip centralizator + coloanele implicite
     */
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

    /**
     * Editate tip de centralizator
     * Se actualizeaza coloanele implicite
     */
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

        return self::withCount('columns')->find($record->id);
    }

    /**
     * Stergerea unui tip de centralizator
     * Stergerea este logica
     * Coloanele raman
     */
    public static function doDelete($input, $record) {

        $record->deleted = 1;
        $record->deleted_by = \Auth::user()->id;
        $record->name = $record->id . '#' . $record->name;
        $record->save();

        return $record;
    }
    
}