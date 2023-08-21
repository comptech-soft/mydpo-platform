<?php

namespace MyDpo\Rules\Nomenclatoare\Livrabile\Cursuri\Cursadresare;

use Illuminate\Contracts\Validation\Rule;
use MyDpo\Models\Nomenclatoare\Livrabile\ELearning\Adresare;

class UniqueName implements Rule {

    public $input = NULL;
    public $action = NULL;
    public $record = NULL;

    public function __construct($action, $input) {
        $this->action = $action;
        $this->input = $input;
    }

    public function passes($attribute, $value) {   

        $q = Adresare::where('name', $this->input['name']);

        if($this->action == 'update')
        {
            $q->where('id', '<>', $this->input['id']);
        }

        $this->record = $q->first();

        return ! $this->record;
    }

    public function message()
    {
        return 'Înregistrarea ' . $this->input['name'] . ' este deja definită.';
    }
}
