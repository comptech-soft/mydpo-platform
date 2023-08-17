<?php

namespace MyDpo\Rules\Nomenclatoare\Country;

use Illuminate\Contracts\Validation\Rule;
use MyDpo\Models\Livrabile\Categories\Category;

class UniqueName implements Rule {

    public $input = NULL;
    public $action = NULL;
    public $record = NULL;

    public function __construct($action, $input) {
        $this->input = $input;
        $this->action = $action;
    }

    public function passes($attribute, $value) {   

        dd($this->action, $this->input);

        $q = Category::where('name', $this->input['name'])->where('type', $this->input['type']);

        if($this->action == 'update')
        {
            $q->where('id', '<>', $this->input['id']);
        }

        $this->record = $q->first();
        
        return ! $this->record;
    }

    public function message()
    {
        return 'Categoria (' . $this->input['name'] . ') este deja definită.';
    }
}
