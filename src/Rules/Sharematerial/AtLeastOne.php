<?php

namespace MyDpo\Rules\Sharematerial;

use Illuminate\Contracts\Validation\Rule;

class AtLeastOne implements Rule {

    public $input = NULL;

    public function __construct($input) {
        $this->input = $input;
    }

    public function passes($attribute, $value) {   

        if(! array_key_exists('customers', $this->input) )
        {
            return FALSE;
        }

        if( count($this->input['customers']) == 0)
        {
            return FALSE;
        }

        $count_users = 0;
        foreach($this->input['customers'] as $customer_id => $users)
        {
            $count_users += count($users);
        }
        
        return $count_users != 0;
    }

    public function message() {
        return 'Pentru trimitere, Trebuie selectat cel puțin un cont destinatar.';
    }
}
