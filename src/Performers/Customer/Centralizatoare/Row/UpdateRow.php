<?php

namespace MyDpo\Performers\Customer\Centralizatoare\Row;

use MyDpo\Helpers\Perform;
use MyDpo\Models\Customer\Centralizatoare\CustomerCentralizatorRow;
use MyDpo\Models\Customer\Centralizatoare\CustomerCentralizatorRowValue;
use MyDpo\Models\Customer\Centralizatoare\CustomerCentralizator;
use MyDpo\Models\Customer_base;
use Carbon\Carbon;

class UpdateRow extends Perform {

    public function Action() {

        $role = $this->getUserRole();

        $input = [
            ...collect($this->input)->except(['rowvalues'])->toArray(),
            'action_at' => $action_at = Carbon::now()->format('Y-m-d'),
            'tooltip' => [
                'text' => 'Editat de :user la :action_at. (:customer)',
                'user'=> \Auth::user()->full_name,
                'customer' => Customer_base::find($this->customer_id)->name,
                'action_at' => $action_at,
            ],
        ];

        $record = CustomerCentralizatorRow::find($this->id);

         
        $record->update($input);
        
        // $customer_centralizator = CustomerCentralizator::find($this->customer_centralizator_id);
        // $status_column_id = $customer_centralizator->status_column_id;

        foreach($this->rowvalues as $i => $input)
        {
            if($input['type'] == 'VISIBILITY')
            {
                $record->visibility = $input['value'];
            }
            else
            {
                if($input['type'] == 'STATUS')
                {
                    $record->status = 'updated';
                }
                else
                {
                    if($input['type'] == 'DEPARTMENT')
                    {
                        $record->department_id = $input['value'];;
                    }
                }
            }

            $rowvalue = CustomerCentralizatorRowValue::find($input['id']);
            $rowvalue->update($input);
        }

        $record->save();

        $this->payload = [
            'record' => CustomerCentralizatorRow::find($record->id),
        ];
    
    }

    public function getUserRole() {
		$user = \Auth::user();
		
		if(config('app.platform') == 'admin')
		{
			return $user->role;
		}
		
		return $user->roles()->wherePivot('customer_id', $this->customer_id)->first();
	}
}