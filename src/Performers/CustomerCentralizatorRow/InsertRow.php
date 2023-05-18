<?php

namespace MyDpo\Performers\CustomerCentralizatorRow;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerCentralizatorRow;
use MyDpo\Models\CustomerCentralizatorRowValue;
use carbon\Carbon;

class InsertRow extends Perform {

    public function Action() {

        $input = collect($this->input)->except(['rowvalues'])->toArray();

        $record = CustomerCentralizatorRow::create([
            ...$input, 
            'props' => [
                'action' => [
                    'name' => 'import',
                    'action_at' => Carbon::now()->format('Y-m-d'),
                    'tooltip' => 'Creat de :user_full_name la :action_at. (:customer_name)',
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
            ],
        ]);

        foreach($this->rowvalues as $i => $input)
        {
            $input['row_id'] = $record->id;

            CustomerCentralizatorRowValue::create($input);
        }

        $this->payload = [
            'record' => CustomerCentralizatorRow::find($record->id),
        ];
    
    }
}