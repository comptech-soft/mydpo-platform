<?php

namespace MyDpo\Rules\Nomenclatoare\Livrabile\Cursuri\Curs;

use Illuminate\Contracts\Validation\Rule;

class IsUrlPresent implements Rule {

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

        if($this->input['type'] == 'link')
        {
            if(! $this->input['url'])
            {
                $this->message = 'Linkul trebuie completat.';
                return FALSE;
            }
        }

        if($this->input['type'] == 'youtube')
        {
            if(! $this->input['url'])
            {
                $this->message = 'Linkul YouTube trebuie completat.';
                return FALSE;
            }
        }

        return TRUE;
    }

    public function message() {
        return $this->message;
    }
}