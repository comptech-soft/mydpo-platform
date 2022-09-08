<?php

namespace MyDpo\Rules\Curs;

use Illuminate\Contracts\Validation\Rule;

class IsUrlPresent implements Rule {

    public $input = NULL;
    public $record = NULL;
    public $message = NULL;

    public function __construct($input) {
        $this->input = $input;
    }

    public function passes($attribute, $value) {   

        if($this->input['type'] == 'link')
        {
            if(! $this->input['url'])
            {
                $this->message = 'Linkul trebuie completat';
                return FALSE;
            }
        }

        if($this->input['type'] == 'youtube')
        {
            if(! $this->input['url'])
            {
                $this->message = 'Linkul YouTube trebuie completat';
                return FALSE;
            }
        }

        return TRUE;
    }

    public function message() {
        return $this->message;
    }
}