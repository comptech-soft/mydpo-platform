<?php

namespace MyDpo\Traits\Customer\Centralizatoare;

use MyDpo\Models\Customer\CustomerDepartment;
use MyDpo\Models\Customer\Centralizatoare\RowValue as CentralizatorRowValue;

trait Rowable {

    protected $statuses = [
        'new' => [
            'color' => 'blue',
            'icon' => 'mdi-file-outline',
        ],

        'updated' => [
            'color' => 'orange',
            'icon' => 'mdi-note-edit-outline',
        ],

        'approved' => [
            'color' => 'green',
            'icon' => 'mdi-check-all',
        ],

        'na' => [
            'color' => 'red',
            'icon' => 'mdi-minus-circle-outline',
        ]
    ];

    public function getHumanStatusAttribute() {
        return $this->statuses[$this->status];
    }

    public function department() {
        return $this->belongsTo(CustomerDepartment::class, 'department_id');
    }

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

    public static function doUpdate($input, $record) {

        dd($input, $record);
    }
}