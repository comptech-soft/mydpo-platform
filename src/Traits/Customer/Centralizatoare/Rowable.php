<?php

namespace MyDpo\Traits\Customer\Centralizatoare;

use MyDpo\Models\Customer\CustomerDepartment;
use MyDpo\Models\Livrabile\TipCentralizator;
use MyDpo\Models\Livrabile\TipCentralizatorColoana;

use MyDpo\Models\Customer\Centralizatoare\Centralizator as CustomerCentralizator;
use MyDpo\Models\Customer\Centralizatoare\RowValue as CentralizatorRowValue;
use MyDpo\Models\Customer\Centralizatoare\Row as CentralizatorRow;

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

    public static function doSetstatus($input, $record) {

        $statuses = collect($input['statuses'])->pluck('text', 'value')->toArray();

        if($input['model'] == 'centralizatoare')
        {
            $rows = CentralizatorRow::whereIn('id', $input['selected_rows'])->update([
                'status' => $input['status'],
                'tooltip' => 'Setat ' . $input['status'] . ' de ' . \Auth::user()->full_name . ' la ' . \Carbon\Carbon::now()->format('d-m-Y'),
            ]);
        }

        return $rows;
    }

    public static function doSetvisibility($input, $record) {

        $statuses = collect($input['statuses'])->pluck('text', 'value')->toArray();

        if($input['model'] == 'centralizatoare')
        {
            $rows = CentralizatorRow::whereIn('id', $input['selected_rows'])->update([
                'visibility' => $input['visibility'],
            ]);
        }

        return $rows;
    }

    public static function doDeleterows($input, $record) {

        if($input['model'] == 'centralizatoare')
        {
            CentralizatorRowValue::whereIn('row_id', $input['selected_rows'])->delete();
            CentralizatorRow::whereIn('id', $input['selected_rows'])->delete();
        }
    }

    public static function doSavewidthssetting($input, $record) {

        if($input['model'] == 'centralizatoare')
        {
            $document = CustomerCentralizator::find($input['document_id']);

            foreach($widths = collect($input['columns'])->pluck('width', 'id')->toArray() as $column_id => $width)
            {
                $column = TipCentralizatorColoana::where('centralizator_id', $document->centralizator_id)->where('id', $column_id)->first();
                $column->width = $width;
                $column->save();

            }

            $tip = TipCentralizator::find($document->centralizator_id);

            $document->current_columns = $tip->columns;
            $document->columns_items = $tip->columns_items;
            $document->columns_tree = $tip->columns_tree;
            $document->columns_with_values = $tip->columns_with_values;    
            $document->save(); 
        }
    } 
}