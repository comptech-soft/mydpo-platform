<?php

namespace MyDpo\Rules\CustomerService;

use Illuminate\Contracts\Validation\Rule;
use MyDpo\Models\CustomerService;

class UniqueOrderService implements Rule {

    public $input = NULL;
    public $record = NULL;

    public function __construct($input)
    {
        $this->input = $input;
    }

    public function passes($attribute, $value)
    {   
        $q = CustomerService::where('order_id', $this->input['order_id'])->where('service_id', $this->input['service_id']);

        if($this->input['id'])
        {
            $q->where('id', '<>', $this->input['id']);
        }

        $this->record = $q->first();

        if($this->record)
        {
            return FALSE;
        }
        
        return TRUE;
    }

    public function message()
    {
        return 'Serviciul este deja în comandă';
    }
}
