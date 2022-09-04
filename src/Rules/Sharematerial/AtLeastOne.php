<?php

namespace MyDpo\Rules\Sharematerial;

use Illuminate\Contracts\Validation\Rule;

class AtLeastOne implements Rule {

    public $input = NULL;

    public function __construct($input) {
        $this->input = $input;
    }

    public function passes($attribute, $value) {   

        dd($this->input);
    }

    public function message()
    {
        return '????';
    }
}
