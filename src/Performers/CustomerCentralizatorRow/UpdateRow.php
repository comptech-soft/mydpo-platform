<?php

namespace MyDpo\Performers\CustomerCentralizatorRow;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerCentralizatorRow;
use MyDpo\Models\CustomerCentralizatorRowValue;
use Carbon\Carbon;

class UpdateRow extends Perform {

    public function Action() {

        $input = collect($this->input)->except(['rowvalues'])->toArray();

        $record = CustomerCentralizatorRow::find($this->id);

        $record->update([
            ...$input, 
            'department_id' => NULL,
            'props' => [
                'action' => [
                    'name' => 'update',
                    'action_at' => Carbon::now()->format('Y-m-d'),
                    'tooltip' => 'Editat de :user_full_name la :action_at. (:customer_name)',
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
            $rowvalue = CustomerCentralizatorRowValue::find($input['id']);

            $rowvalue->update($input);
        }

        $this->payload = [
            'record' => CustomerCentralizatorRow::find($record->id),
        ];
    
    }
}