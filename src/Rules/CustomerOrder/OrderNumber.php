<?php

namespace MyDpo\Rules\CustomerOrder;

use Illuminate\Contracts\Validation\Rule;
use MyDpo\Models\CustomerOrder;

class OrderNumber implements Rule {

    public $input = NULL;
    public $order = NULL;

    public function __construct($input)
    {
        $this->input = $input;
    }

    public function passes($attribute, $value)
    {   

        $q = CustomerOrder::where('number', $this->input['number'])->where('contract_id', $this->input['contract_id']);

        if($this->input['id'])
        {
            $q->where('id', '<>', $this->input['id']);
        }

        $this->order = $q->first();

        if($this->order)
        {
            return FALSE;
        }
        
        return TRUE;
    }

    public function message()
    {
        return 'The order number (' . $this->input['number'] . ') has already been taken (Contract: ' . $this->order->contract->number . ').';
    }
}
