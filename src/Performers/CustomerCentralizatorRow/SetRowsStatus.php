<?php

namespace MyDpo\Performers\CustomerCentralizatorRow;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerCentralizator;
use MyDpo\Models\CustomerCentralizatorRow;
use MyDpo\Models\CustomerCentralizatorRowValue;
use Carbon\Carbon;

class SetRowsStatus extends Perform {

    public function Action() {

        $records = NULL;
        
        if(!! count($this->selected_rows) )
        {
            $rows = CustomerCentralizatorRow::whereIn('id', $this->selected_rows)->get();

            foreach($rows as $i => $row)
            {

                $props = !! $row->props ? $row->props : []; 
                $props = [
                    ...$props,
                    'action' => [
                        'name' => 'visibility',
                        'action_at' => Carbon::now()->format('Y-m-d'),
                        'tooltip' => 'Setat ' . $this->status . ' de :user_full_name la :action_at. (:customer_name)',
                        'user' => [
                            'id' => \Auth::user()->id,
                            'full_name' => \Auth::user()->full_name,
                            'role' => [
                                'name' => \Auth::user()->role->name,
                            ]
                        ],
                        'customer' => [
                            'id' => $this->customer_id,
                            'name' => $this->customer,
                        ],
                    ],
                ];

                $row->props = $props;
                $row->save();
            }

            $customer_centralizator = CustomerCentralizator::find($this->customer_centralizator_id);

            $records = CustomerCentralizatorRowValue::where('column_id', $customer_centralizator->status_column_id)
                ->whereIn('row_id', $this->selected_rows)
                ->update([
                    'value' => $this->status,
                ]);
        }

        $this->payload = [
            'record' => $records,
            'visible_column_id' => $customer_centralizator->status_column_id,
            'input' => $this->input,
        ];
    
    }
}