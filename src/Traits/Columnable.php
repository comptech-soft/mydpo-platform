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

        dd($type);
        
        $record = CentralizatorColoana::where('centralizator_id', $this->id)->where('type', $type)->delete();
    }

    // public function AddColumnVizibilitate() {
    //     $this->AddColumn('Vizibilitate', 'VISIBILITY', -100, 150);
    // }

    public function AddColumn($caption, $type, $order_no, $width) {
        
        $input = [
            'centralizator_id' => $this->id,
            'caption' => $caption,
            'slug' => md5($caption . time()),
            'is_group' => 0,
            'group_id' => NULL,
            'type' =>  $type,
            'order_no' => $order_no,
            'width' => $width,
        ];

        dd($input);

        // $record = CentralizatorColoana::where('centralizator_id', $this->id)->where('type', $type)->first();

        // if(!! $record)
        // {
        //     $record->update($input);
        // }
        // else
        // {
        //     $record = CentralizatorColoana::create($input);
        // }

    }
    
}