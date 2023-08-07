<?php

namespace MyDpo\Performers\Customer\Centralizatoare\Row;

use MyDpo\Helpers\Perform;
use MyDpo\Models\Customer\Centralizatoare\CustomerCentralizator;
use MyDpo\Models\Customer\Centralizatoare\CustomerCentralizatorRow;
use MyDpo\Models\Customer\Centralizatoare\CustomerCentralizatorRowValue;
use Carbon\Carbon;

class SetRowsStatus extends Perform {

    public function Action() {

        $records = NULL;

        if(!! count($this->selected_rows) )
        {

            // $role = $this->getUserRole();

            $statuses = collect($this->statuses)->pluck('text', 'value')->toArray();

            $rows = CustomerCentralizatorRow::whereIn('id', $this->selected_rows)->get();

            foreach($rows as $i => $row)
            {

                $row->status = $this->status;
                $row->action_at = $action_at = Carbon::now()->format('Y-m-d');
                $row->tooltip = [
                    'text' => 'Setat ' . $statuses[$this->status] . ' de :user la :action_at. (:customer)',
                    'user'=> \Auth::user()->full_name,
                    'customer' => $this->customer,
                    'action_at' => $action_at,
                ];

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

    // public function getUserRole() {
	// 	$user = \Auth::user();
		
	// 	if(config('app.platform') == 'admin')
	// 	{
	// 		return $user->role;
	// 	}
		
	// 	return $user->roles()->wherePivot('customer_id', $this->customer_id)->first();
	// }
}