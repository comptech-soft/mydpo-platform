<?php

namespace MyDpo\Traits;

trait Columnable { 

    public function DeleteColumnStatus() {
        $this->DeleteColumn('STATUS');
    }

    public function DeleteColumnDepartament() {
        $this->DeleteColumn('DEPARTMENT');
    }

    public function DeleteColumnVizibilitate() {
        $this->DeleteColumn('VISIBILITY');
    }

    public function DeleteColumn($type) {

        $model = new ($this->columnsDefinition['model']);
        
        $record = $model->where($this->columnsDefinition['foreign_key'], $this->id)->whereType($type)->first();

        if(!! $record)
        {
            return $record->delete();
        }

        return $record;
    }

    public function AddColumnVizibilitate() {
        $this->AddColumn('Vizibilitate', 'VISIBILITY', -100, 150);
    }

    public function AddColumnStatus() {
        $this->AddColumn('Status', 'STATUS', -90, 150);
    }

    public function AddColumnDepartament() {
        $this->AddColumn('Departament', 'DEPARTMENT', -80, 200);
    }

    public function AddColumn($caption, $type, $order_no, $width) {
        
        $model = new ($this->columnsDefinition['model']);

        $record = $model->where($this->columnsDefinition['foreign_key'], $this->id)->whereType($type)->first();

        $input = [
            $this->columnsDefinition['foreign_key'] => $this->id,
            'caption' => $caption,
            'slug' => md5($caption . time()),
            'is_group' => 0,
            'group_id' => NULL,
            'type' =>  $type,
            'order_no' => $order_no,
            'width' => $width,
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
    
}