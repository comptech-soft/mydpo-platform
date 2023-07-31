<?php

namespace MyDpo\Rules\Registru;

use Illuminate\Contracts\Validation\Rule;
use MyDpo\Models\Livrabile\Registru;

class UniqueName implements Rule {

    public $input = NULL;
    public $action = NULL;
    public $record = NULL;

    public function __construct($action, $input) {
        $this->input = $input;
        $this->action = $action;
    }

    public function passes($attribute, $value) {   

        $q = Registru::where('name', $this->input['name'])->where('type', $this->input['type']);

        if($this->action == 'update')
        {
            $q->where('id', '<>', $this->input['id']);
        }

        $this->record = $q->first();
        
        return ! $this->record;
    }

    public function message()
    {
        return 'Registrul (' . $this->input['name'] . ') este deja definit.';
    }
}

