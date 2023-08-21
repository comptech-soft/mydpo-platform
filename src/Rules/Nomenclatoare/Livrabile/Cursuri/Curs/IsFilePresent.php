<?php

namespace MyDpo\Rules\Nomenclatoare\Livrabile\Cursuri\Curs;

use Illuminate\Contracts\Validation\Rule;

class IsFilePresent implements Rule {

    public $input = NULL;
    public $record = NULL;
    public $action = NULL;
    public $message = NULL;

    public function __construct($action, $input) {
        $this->action = $action;
        $this->input = $input;
    }

    public function passes($attribute, $value) {   
        if(! $this->input['type'])
        {
            $this->message = 'Selectați tipul cursului.';
            return FALSE;
        }

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