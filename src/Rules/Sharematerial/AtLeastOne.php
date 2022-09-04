<?php

namespace MyDpo\Rules\Sharematerial;

use Illuminate\Contracts\Validation\Rule;

class AtLeastOne implements Rule {

    public $input = NULL;

    public function __construct($input) {
        $this->input = $input;
    }

    public function passes($attribute, $value) {   

        if(! array_key_exists('customers', $this->input) )
        {
            return FALSE;
        }

        if( count($this->input['custmers']) == 0)
        {
            return FALSE;
        }

        dd($this->input['customers']);
        // dd($this->input);
    }

    public function message()
    {
        return '????';
    }
}
