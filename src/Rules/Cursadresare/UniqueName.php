<?php

namespace MyDpo\Rules\Cursadresare;

use Illuminate\Contracts\Validation\Rule;
use MyDpo\Models\Cursadresare;

class UniqueName implements Rule {

    public $input = NULL;
    public $record = NULL;

    public function __construct($input) {
        $this->input = $input;
    }

    public function passes($attribute, $value) {   

        $q = Cursadresare::where('name', $this->input['name']);

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
        return 'Înregistrarea este deja definită.';
    }
}
