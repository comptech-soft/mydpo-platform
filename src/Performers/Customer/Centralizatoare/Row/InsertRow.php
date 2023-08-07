<?php

namespace MyDpo\Performers\Customer\Centralizatoare\Row;

use MyDpo\Helpers\Perform;
use MyDpo\Models\Customer\Centralizatoare\CustomerCentralizatorRow;
use MyDpo\Models\Customer\Centralizatoare\CustomerCentralizatorRowValue;
use MyDpo\Models\Customer_base;
use Carbon\Carbon;

class InsertRow extends Perform {

    public function Action() {
		
        $role = $this->getUserRole();
		
        $input = [
            ...collect($this->input)->except(['rowvalues'])->toArray(),
            'action_at' => $action_at = Carbon::now()->format('Y-m-d'),
            'tooltip' => [
                'text' => 'Creat de :user la :action_at. (:customer)',
                'user'=> \Auth::user()->full_name,
                'customer' => Customer_base::find($this->customer_id)->name,
                'action_at' => $action_at,
            ],
        ];
        
        $record = CustomerCentralizatorRow::create($input);

        foreach($this->rowvalues as $i => $input)
        {
            $input['row_id'] = $record->id;

            $input['column'] = $input['type'];

            if($input['type'] == 'VISIBILITY')
            {
                $record->visibility = $input['value'];
            }
            else
            {
                if($input['type'] == 'STATUS')
                {
                    $record->status = 'new';
                }
                else
                {
                    if($input['type'] == 'DEPARTMENT')
                    {
                        $record->department_id = $input['value'];;
                    }
                }
            }
            
            CustomerCentralizatorRowValue::create($input);
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