<?php

namespace MyDpo\Performers\UserCustomer;

use MyDpo\Helpers\Perform;
use MyDpo\Models\UserCustomer;

class UpdateUserCustomers extends Perform {

    public function Action() {

        dd($this->input);

        UserCustomer::where('user_id', $this->input['user_id'])->delete();

        if(! array_key_exists('customers_ids', $this->input))
        {
            $this->input['customers_ids'] = [];
        }

        foreach($this->input['customers_ids'] as $i => $customer_id)
        {
            UserCustomer::create([
                'user_id' => $this->input['user_id'],
                'customer_id' => $customer_id,
                'created_by' => \Auth::user()->id,
            ]);
        }
        
    }

} 