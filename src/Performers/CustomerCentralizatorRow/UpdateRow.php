<?php

namespace MyDpo\Performers\CustomerCentralizatorRow;

use MyDpo\Helpers\Perform;
use MyDpo\Models\Customer\CustomerCentralizatorRow;
use MyDpo\Models\Customer\CustomerCentralizatorRowValue;
use MyDpo\Models\Customer\CustomerCentralizator;
use Carbon\Carbon;

class UpdateRow extends Perform {

    public function Action() {

        $input = collect($this->input)->except(['rowvalues'])->toArray();

        $record = CustomerCentralizatorRow::find($this->id);

        if(config('app.platform') == 'admin')
        {
            $role = \Auth::user()->role;
        }
        else
        {
            $role = \Auth::user()->roles()->wherePivot('customer_id', $this->customer_id)->first();
        }
         
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
                            'id' => $role ? $role->id : NULL,
                            'name' => $role ? $role->name : NULL,
                        ]
                    ],
                    'customer' => [
                        'id' => $this->customer_id,
                        'name' => $this->customer,
                    ],
                ],
            ],
        ]);
        
        $customer_centralizator = CustomerCentralizator::find($this->customer_centralizator_id);
        $status_column_id = $customer_centralizator->status_column_id;

        foreach($this->rowvalues as $i => $input)
        {
            if($input['column_id'] == $status_column_id)
            {
                $input['value'] = 'updated';
            }

            $rowvalue = CustomerCentralizatorRowValue::find($input['id']);
            $rowvalue->update($input);
        }

        $this->payload = [
            'record' => CustomerCentralizatorRow::find($record->id),
        ];
    
    }
}