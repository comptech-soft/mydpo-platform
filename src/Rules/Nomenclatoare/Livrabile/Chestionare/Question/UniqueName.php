<?php

namespace MyDpo\Rules\Nomenclatoare\Livrabile\Chestionare\Question;

use Illuminate\Contracts\Validation\Rule;
use MyDpo\Models\Nomenclatoare\Livrabile\Chestionare\Question;

class UniqueName implements Rule {

    public $input = NULL;
    public $action = NULL;
    public $record = NULL;

    public function __construct($action, $input) {
        $this->action = $action;
        $this->input = $input;
    }

    public function passes($attribute, $value) {   

        $q = Question::where('name', $this->input['name']);

        if($this->action == 'update')
        {
            $q->where('id', '<>', $this->input['id']);
        }

        $this->record = $q->first();

        return ! $this->record;
    }

    public function message()
    {
        return 'Numele de întrebare ' . $this->input['name'] . ' este deja definit.';
    }
}
