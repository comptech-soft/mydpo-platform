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

        foreach($input['rowvalues'] as $i => $rowvalueinput)
        {
            $input['rowvalues'][$i]['row_id'] = $rowvalueinput['row_id'] = $row->id;

            $input['rowvalues'][$i]['column'] = $rowvalueinput['column'] = $rowvalueinput['type'];

            if($input['model'] == 'centralizatoare')
            {
                $rowvalue = CentralizatorRowValue::create($rowvalueinput);
                $input['rowvalues'][$i]['id'] = $rowvalue->id;
            }
        }

        $row->props = [
            'rowvalues' => $input['rowvalues'],
        ];
        $row->save();

        return self::find($row->id);
    }

    public static function doUpdate($input, $record) {

        $record->update([
            ...$input,
            'props' => [
                'rowvalues' => $input['rowvalues'],
            ] 
        ]);

        foreach($input['rowvalues'] as $i => $rowvalueinput)
        {
            if($input['model'] == 'centralizatoare')
            {
                $rowvalue = CentralizatorRowValue::find($rowvalueinput['id']);
                $rowvalue->update($rowvalueinput);
            }
        }

        return self::find($record->id);
    }

    public static function doDelete($input, $record) {
        if($input['model'] == 'centralizatoare')
        {
            CentralizatorRowValue::where('row_id', $input['id'])->delete();
            $record->delete();
        }
    }

    public function doSetstatus($input, $record) {

        dd($input, $record);
    }
}