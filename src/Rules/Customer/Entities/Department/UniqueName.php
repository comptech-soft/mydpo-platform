<?php

namespace MyDpo\Rules\Customer\Entities\Department;

use Illuminate\Contracts\Validation\Rule;
use MyDpo\Models\Customer\Departments\Department;

class UniqueName implements Rule {

    public $input = NULL;
    public $record = NULL;

    public function __construct($input) {
        $this->input = $input;
    }

    public function passes($attribute, $value) {   

        $q = Department::where('departament', $this->input['departament'])->where('customer_id', $this->input['customer_id']);

        if(array_key_exists('id', $this->input) && $this->input['id'])
        {
            $q->where('id', '<>', $this->input['id']);
        }

        $this->record = $q->first();

        return ! $this->record;
    }

    public function message()
    {
        return 'Departamentul (' . $this->input['departament'] . ') este deja definit.';
    }
}
