<?php

namespace MyDpo\Performers\Customer\Centralizatoare\Row;

use MyDpo\Helpers\Perform;
use MyDpo\Models\Customer\Centralizatoare\CustomerCentralizator;
use MyDpo\Models\Customer\Centralizatoare\CustomerCentralizatorRow;
use MyDpo\Models\Customer\Centralizatoare\CustomerCentralizatorRowValue;
use Carbon\Carbon;

class SetRowsVisibility extends Perform {

    public function Action() {

        $records = NULL;

        if(!! count($this->selected_rows) )
        {

            $role = $this->getUserRole();

            $items = collect($this->items)->pluck('text', 'value')->toArray();

            $rows = CustomerCentralizatorRow::whereIn('id', $this->selected_rows)->get();

            foreach($rows as $i => $row)
            {

                $row->visibility = $this->visibility;
                $row->action_at = $action_at = Carbon::now()->format('Y-m-d');
                $row->tooltip = [
                    'text' => 'Setat ' . $items[$this->visibility] . ' de :user la :action_at. (:customer)',
                    'user'=> \Auth::user()->full_name,
                    'customer' => $this->customer,
                    'action_at' => $action_at,
                ];

                $row->save();
            }
            
            $customer_centralizator = CustomerCentralizator::find($this->customer_centralizator_id);

            $records = CustomerCentralizatorRowValue::where('column_id', $customer_centralizator->visibility_column_id)
                ->whereIn('row_id', $this->selected_rows)
                ->update([
                    'value' => $this->visibility,
                ]);
        }

        $this->payload = [
            'record' => $records,
            'visible_column_id' => $customer_centralizator->visible_column_id,
            'input' => $this->input,
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