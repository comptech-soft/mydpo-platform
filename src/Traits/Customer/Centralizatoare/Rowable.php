<?php

namespace MyDpo\Traits\Customer\Centralizatoare;

use MyDpo\Models\Customer\Departments\Department;
use MyDpo\Models\Customer\Customer;
use MyDpo\Models\Livrabile\TipCentralizator;
use MyDpo\Models\Livrabile\Centralizatoare\TipCentralizatorColoana;

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
        ],

        'unkown' => [
            'color' => 'grey',
            'icon' => 'mdi-help',
        ]
    ];

    public function getHumanStatusAttribute() {
        $status = preg_replace('/[\x00-\x1F\x7F\xC2\xA0]/', '', $this->status);

        if(array_key_exists($status, $this->statuses) )
        {
            return $this->statuses[ $status ];
        }

        return $this->statuses['unkown'];
    }

    public function department() {
        return $this->belongsTo(Department::class, 'department_id');
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
        
        if(! array_key_exists('rowvalues', $input) )
        {
            $input['rowvalues'] = [];
        }

        dd($input);

        $record->update([
            ...$input,
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

        $action_at = \Carbon\Carbon::now();

        $tooltip = [
            'text' => $statuses[$input['status']] . ' de :full_name/:role la :action_at. (:customer)',
            'values' => [
                'full_name' => \Auth::user()->full_name,
                'action_at' => $action_at->format('d.m.Y'),
                'role' => \Auth::user()->role->name,
                'customer' => config('app.platform') == 'b2b' ? Customer::find($input['customer_id'])->name : 'Decalex',
            ]
        ];

        $rows = self::$myclasses['row']::whereIn('id', $input['selected_rows'])
            ->update([
                'status' => $input['status'],
                'tooltip' => $tooltip,
            ]);
    }

    /**
     * Setarea vizibilitatii unui rand din centralizator | registru
     */
    public static function doSetvisibility($input, $record) {

        $rows = self::$myclasses['row']::whereIn('id', $input['selected_rows'])
            ->update(
                [
                    'visibility' => $input['visibility'],
                ]
            );
    }

    /**
     *  Stergerea randurilor din centralizator | registru
     */
    public static function doDeleterows($input, $record) {

        self::$myclasses['rowvalue']::whereIn('row_id', $input['selected_rows'])->delete();
        self::$myclasses['row']::whereIn('id', $input['selected_rows'])->delete();
    
    }

    /**
     * Setare latimi coloane
     */
    public static function doSavewidthssetting($input, $record) {

        $document = self::$myclasses['document']::find($input['document_id']);

        $fields = [
            'centralizatoare' => 'centralizator_id',
            'registre' => 'register_id',
        ];

        foreach($widths = collect($input['columns'])->pluck('width', 'id')->toArray() as $column_id => $width)
        {
            $column = self::$myclasses['tipcoloana']::where($fields[$input['model']], $document->{$fields[$input['model']]})->where('id', $column_id)->first();
            $column->width = $width;
            $column->save();
        }

        $tip = self::$myclasses['tip']::find($document->{$fields[$input['model']]});

        $document->current_columns = $tip->columns;
        $document->columns_items = $tip->columns_items;
        $document->columns_tree = $tip->columns_tree;
        $document->columns_with_values = $tip->columns_with_values;    
        $document->save(); 
    } 

    /**
     * Setare acces la randurile documentului
     * Accesul se face pe useri + departamente
     */
    public static function doAccountaccess($input, $record) {
        
        $fields = [
            'centralizatoare' => [
                'customer_centralizator_id', 
                'centralizator_id'
            ],
            'registre' => [
                'customer_registru_id', 'register_id'
            ],
        ];

        self::$myclasses['access']::where($fields[$input['model']][0], $input['document_id'])->delete();

        if(array_key_exists('departments', $input) && !! count($input['departments']) )
        {
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
                self::$myclasses['access']::create([
                    $fields[$input['model']][0] => $input['document_id'],
                    'customer_id' => $input['customer_id'],
                    $fields[$input['model']][1] => $input['tip_id'],
                    'user_id' => $user_id,
                    'departamente' => $departamente,                    
                ]);
            }

            if(count($users) > 0)
            {
                call_user_func([self::$myclasses['document'], 'SendNotificationToUsers'], [
                    'model' => $input['model'],
                    'tip_id' => $input['tip_id'],
                    'document_id' => $input['document_id'],
                    'user_ids' => array_keys($users),
                    'customer_id' => $input['customer_id'],
                ]); 
            }
        }
    }

}