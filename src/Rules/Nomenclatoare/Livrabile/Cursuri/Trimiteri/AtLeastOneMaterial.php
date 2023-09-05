<?php

namespace MyDpo\Rules\Nomenclatoare\Livrabile\Cursuri\Trimiteri;

use Illuminate\Contracts\Validation\Rule;

class AtLeastOneMaterial implements Rule {

    public $input = NULL;

    public function __construct($input) {
        $this->input = $input;
    }

    public function passes($attribute, $value) {   

        if(! array_key_exists('materiale_trimise', $this->input) )
        {
            return FALSE;
        }

        if( count($this->input['materiale_trimise']) == 0)
        {
            return FALSE;
        }
        
        return TRUE;
    }

    public function message() {
        return 'Pentru trimitere, trebuie selectat cel puțin un ' . $this->input['type'] . '.';
    }
}
