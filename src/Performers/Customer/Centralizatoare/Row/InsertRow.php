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
            'props' => [
                'action' => [
                    'name' => 'new',
                    'action_at' => Carbon::now()->format('Y-m-d'),
                    'tooltip' => 'Creat de :user_full_name la :action_at. (:customer_name)',
                    'user' => [
                        'id' => \Auth::user()->id,
                        'full_name' => \Auth::user()->full_name,
                        'role' => [
							'id' => !! $role ? $role->id : NULL,
                            'name' => !! $role ? $role->name : NULL,
                        ]
                    ],
                    'customer' => [
                        'id' => $this->customer_id,
                        'name' => Customer_base::find($this->customer_id)->name,
                    ],
                ],
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
                    $record->status = $input['value'];
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