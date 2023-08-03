<?php

namespace MyDpo\Performers\CustomerCentralizatorRow;

use MyDpo\Helpers\Perform;
use MyDpo\Models\Customer\CustomerCentralizatorRow;
use MyDpo\Models\Customer\CustomerCentralizatorRowValue;
use Carbon\Carbon;

class InsertRow extends Perform {

    public function Action() {
		
		$role = $this->getUserRole();
		
        $input = collect($this->input)->except(['rowvalues'])->toArray();
        
        $record = CustomerCentralizatorRow::create([
            ...$input, 
            'props' => [
                'action' => [
                    'name' => 'insert',
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
	
	public function getUserRole() {
		$user = \Auth::user();
		
		if(config('app.platform') == 'admin')
		{
			return $user->role;
		}
		
		return $user->roles()->wherePivot('customer_id', $this->customer_id)->first();
	}
}