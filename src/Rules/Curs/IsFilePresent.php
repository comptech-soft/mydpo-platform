<?php

namespace MyDpo\Rules\Curs;

use Illuminate\Contracts\Validation\Rule;

class IsFilePresent implements Rule {

    public $input = NULL;
    public $record = NULL;
    public $message = NULL;

    public function __construct($input) {
        $this->input = $input;
    }

    public function passes($attribute, $value) {   
        if($this->input['type'] == 'fisier')
        {
            if(! $this->input['file'])
            {
                $this->message = 'Fișerul trebuie selectat.';
                return FALSE;
            }
        }

        return TRUE;
    }

    public function message() {
        return $this->message;
    }
}