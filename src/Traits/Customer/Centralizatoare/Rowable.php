<?php

namespace MyDpo\Traits\Customer\Centralizatoare;

use MyDpo\Models\Customer\CustomerDepartment;
use MyDpo\Models\Livrabile\TipCentralizator;
use MyDpo\Models\Livrabile\TipCentralizatorColoana;

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

    /**
     * Inserarea unui rand nou in centralizator | registru
     */
    public static function doInsert($input, $record) {

        if(! array_key_exists('rowvalues', $input) )
        {
            $input['rowvalues'] = [];
        }

        $row = self::create([
            ...$input,
            'tooltip' => 'Creat de :full_name la :action_at. (:customer)',
            'props' => [
                'rowvalues' => $input['rowvalues'],
            ] 
        ]);

        foreach($input['rowvalues'] as $i => $rowvalueinput)
        {
            $input['rowvalues'][$i]['row_id'] = $rowvalueinput['row_id'] = $row->id;
            $input['rowvalues'][$i]['column'] = $rowvalueinput['column'] = $rowvalueinput['type'];
            
            $rowvalue = self::$myclasses['rowvalue']::create($rowvalueinput);
            $input['rowvalues'][$i]['id'] = $rowvalue->id;            
        }

        $row->props = [
            'rowvalues' => $input['rowvalues'],
        ];
        
        $row->save();

        return self::find($row->id);
    }

    /**
     * Editarea unui rand din centralizator | registru
     */
    public static function doUpdate($input, $record) {
        $record->update([
            ...$input,
            'tooltip' => 'Editat de full_name la :action_at. (:customer)',
            'props' => [
                'rowvalues' => $input['rowvalues'],
            ] 
        ]);

        foreach($input['rowvalues'] as $i => $rowvalueinput)
        {
            $rowvalue = self::$myclasses['rowvalue']::find($rowvalueinput['id']);
            $rowvalue->update($rowvalueinput);
        }

        return self::find($record->id);
    }

    /**
     * Stergerea unui rand din centralizator | registru
     */
    public static function doDelete($input, $record) {
        
        self::$myclasses['rowvalue']::where('row_id', $input['id'])->delete();
        $record->delete();

    }

    /**
     * Setarea statusului unui rand din centralizator | registru
     */
    public static function doSetstatus($input, $record) {

        $statuses = collect($input['statuses'])->pluck('text', 'value')->toArray();

        switch( $input['model'] )
        {
            case 'centralizatoare': 
                $rows = CentralizatorRow::whereIn('id', $input['selected_rows'])->update([
                    'status' => $input['status'],
                    'tooltip' => 'Setat ' . $input['status'] . ' de ' . \Auth::user()->full_name . ' la ' . \Carbon\Carbon::now()->format('d-m-Y'),
                ]);
                break;

            case 'registre':
                $rows = RegistruRow::whereIn('id', $input['selected_rows'])->update([
                    'status' => $input['status'],
                    'tooltip' => 'Setat ' . $input['status'] . ' de ' . \Auth::user()->full_name . ' la ' . \Carbon\Carbon::now()->format('d-m-Y'),
                ]);
                break;

            default: 
                throw new \Exception('Invalid model [' . $input['model'] .  ']');
        }
        
        return $rows;
    }

    public static function doSetvisibility($input, $record) {

        $statuses = collect($input['statuses'])->pluck('text', 'value')->toArray();

        switch( $input['model'] )
        {
            case 'centralizatoare': 
                $rows = CentralizatorRow::whereIn('id', $input['selected_rows'])->update([
                    'visibility' => $input['visibility'],
                ]);
                break;

            case 'registre':
                $rows = RegistruRow::whereIn('id', $input['selected_rows'])->update([
                    'visibility' => $input['visibility'],
                ]);
                break;

            default: 
                throw new \Exception('Invalid model [' . $input['model'] .  ']');
        }

        return $rows;
    }

    public static function doDeleterows($input, $record) {

        switch( $input['model'] )
        {
            case 'centralizatoare': 
                CentralizatorRowValue::whereIn('row_id', $input['selected_rows'])->delete();
                CentralizatorRow::whereIn('id', $input['selected_rows'])->delete();
                break;

            case 'registre':
                RegistruRowValue::whereIn('row_id', $input['selected_rows'])->delete();
                RegistruRow::whereIn('id', $input['selected_rows'])->delete();
                break;

            default: 
                throw new \Exception('Invalid model [' . $input['model'] .  ']');
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

    public static function doAccountaccess($input, $record) {

        if(array_key_exists('departments', $input) && !! count($input['departments']) )
        {
            if($input['model'] == 'centralizatoare')
            {
                CentralizatorAccess::where('customer_centralizator_id', $input['document_id'])->delete();
            }

            $users = [];

            foreach($input['departments'] as $i => $item)
            {
                $parts = explode('#', $item);

                $user_id = $parts[0];
                $department_id = $parts[1];

                if( ! array_key_exists($user_id, $users) )
                {
                    $users[$user_id] = [];
                }

                $users[$user_id][] = $department_id;
            }

            foreach($users as $user_id => $departamente)
            {
                if($input['model'] == 'centralizatoare')
                {
                    CentralizatorAccess::create([
                        'customer_centralizator_id' => $input['document_id'],
                        'customer_id' => $input['customer_id'],
                        'centralizator_id' => $input['tip_id'],
                        'user_id' => $user_id,
                        'departamente' => $departamente,                    
                    ]);
                }
            }
        }
    }
}