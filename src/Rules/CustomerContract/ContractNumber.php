<?php

namespace MyDpo\Rules\CustomerContract;

use Illuminate\Contracts\Validation\Rule;
use MyDpo\Models\CustomerContract;

class ContractNumber implements Rule {

    public $input = NULL;
    public $contract = NULL;

    public function __construct($input)
    {
        $this->input = $input;
    }

    public function passes($attribute, $value)
    {   
        $q = CustomerContract::where('number', $this->input['number']); 

        if(array_key_exists('id', $this->input) && $this->input['id'])
        {
            $q->where('id', '<>', $this->input['id']);
        }

        $this->contract = $q->first();

        if($this->contract)
        {
            return FALSE;
        }
        
        return TRUE;
    }

    public function message()
    {
        return 'Contractul (' . $this->input['number'] . ') ese deja atribuit (' . $this->contract->customer->name . ').';
    }
}
