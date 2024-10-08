<?php

namespace MyDpo\Rules\Nomenclatoare\Livrabile\Chestionare\Chestionar;

use Illuminate\Contracts\Validation\Rule;
use MyDpo\Models\Livrabile\Chestionare\Chestionar;

class UniqueName implements Rule {

    public $input = NULL;
    public $action = NULL;
    public $record = NULL;

    public function __construct($action, $input) {
        $this->action = $action;
        $this->input = $input;
    }

    public function passes($attribute, $value) {   

        $q = Chestionar::where('name', $this->input['name'])->whereDeleted(0);

        if($this->action == 'update')
        {
            $q->where('id', '<>', $this->input['id']);
        }

        $this->record = $q->first();

        return ! $this->record;
    }

    public function message()
    {
        return 'Numele de chestionar ' . $this->input['name'] . ' este deja definit.';
    }
}
