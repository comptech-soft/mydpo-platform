<?php

namespace MyDpo\Traits;

trait Centralizatorcolumnable { 

    public function getArrayCaptionAttribute() {
        return explode('#', $this->caption);
    }

    public function getNotIsClickableAttribute() {
        return in_array($this->type, ['VISIBILITY', 'STATUS', 'FILES', 'DEPARTMENT', 'NRCRT', 'EMPTY']);
    }

    public function getNotIsInListAttribute() {
        return in_array($this->type, ['EMPTY']);
    }

    public function getHumanTypeAttribute() {
        $types = [
            'NRCRT' => 'Număr curent',
            'VISIBILITY' => 'Vizibilitate',
            'STATUS' => 'Status',
            'FILES' => 'Fișiere',
            'DEPARTMENT' => 'Departament',
            'C' => 'Text',
            'N' => 'Număr',
            'O' => 'Opțiuni',
            'D' => 'Dată calendaristică',
            'T' => 'Dată calendaristică și oră',
        ];
        return array_key_exists($this->type, $types) ? $types[$this->type] : $this->type;
    }

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