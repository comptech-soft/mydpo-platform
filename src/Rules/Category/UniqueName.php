<?php

namespace MyDpo\Rules\Category;

use Illuminate\Contracts\Validation\Rule;
use MyDpo\Models\Category;

class UniqueName implements Rule {

    public $input = NULL;
    public $record = NULL;

    public function __construct($input) {
        $this->input = $input;
    }

    public function passes($attribute, $value) {   

        $q = Category::where('name', $this->input['name'])->where('type', $this->input['type']);

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
        return 'Categoria (' . $this->input['name'] . ') este deja definit.';
    }
}
