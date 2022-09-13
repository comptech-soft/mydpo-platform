<?php

namespace MyDpo\Rules\CustomerAccount;

use Illuminate\Contracts\Validation\Rule;
use MyDpo\Models\CustomerAccount;

class UniqueUser implements Rule {

    public $input = NULL;
    public $record = NULL;

    public function __construct($input) {
        $this->input = $input;
    }

    public function passes($attribute, $value) {   

        $q = CustomerAccount::where('name', $this->input['customer_id'])->where('user_id', $this->input['user_id'])->where('deleted', 0);

        if(array_key_exists('id', $this->input) && $this->input['id'])
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
        return 'Utiliatorul este deja definit.';
    }
}
