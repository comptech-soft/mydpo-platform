<?php

namespace MyDpo\Performers\CustomerCentralizatorRow;

use MyDpo\Helpers\Perform;
// use MyDpo\Models\CustomerCurs;

class SetRowsVisibility extends Perform {


    public function Action() {

        dd($this->input);

        // $customer_id = $this->input['customer_id'];
        
        // $count = CustomerCurs::where('customer_id', $customer_id)->has('curs')->count();
        
        // $this->payload = [
        //     'count' => $count,
        // ];
    
    }
}