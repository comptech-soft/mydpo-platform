<?php

namespace MyDpo\Rules\Livrabile\Service;

use Illuminate\Contracts\Validation\Rule;
use MyDpo\Models\Livrabile\Services\Service;

class UniqueName implements Rule {

    public $input = NULL;
    public $action = NULL;
    public $record = NULL;

    public function __construct($action, $input) {
        $this->input = $input;
        $this->action = $action;
    }

    public function passes($attribute, $value) {   

        $q = Service::where('name', $this->input['name']);

        if($this->action == 'update')
        {
            $q->where('id', '<>', $this->input['id']);
        }

        $this->record = $q->first();
        
        return ! $this->record;
    }

    public function message()
    {
        return 'Serviciul (' . $this->input['name'] . ') este deja definit.';
    }
}
