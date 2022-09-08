<?php

namespace MyDpo\Rules\Curs;

use Illuminate\Contracts\Validation\Rule;

class IsUrlPresent implements Rule {

    public $input = NULL;
    public $record = NULL;

    public function __construct($input) {
        $this->input = $input;
    }

    public function passes($attribute, $value) {   

        dd($this->input);
    }

    public function message()
    {
        return 'Categoria (' . $this->input['name'] . ') este deja definită.';
    }
}