<?php

namespace MyDpo\Rules\Nomenclatoare\Country;

use Illuminate\Contracts\Validation\Rule;
use MyDpo\Models\System\Country;

class UniqueName implements Rule {

    public $input = NULL;
    public $action = NULL;
    public $record = NULL;

    public function __construct($action, $input) {
        $this->input = $input;
        $this->action = $action;
    }

    public function passes($attribute, $value) {   

        $q = Country::where('name', $this->input['name']);

        if($this->action == 'update')
        {
            $q->where('id', '<>', $this->input['id']);
        }

        $this->record = $q->first();
        
        return ! $this->record;
    }

    public function message()
    {
        return 'Țara (' . $this->input['name'] . ') este deja definită.';
    }
}
