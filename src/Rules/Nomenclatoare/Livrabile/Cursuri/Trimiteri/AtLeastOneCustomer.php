<?php

namespace MyDpo\Rules\Nomenclatoare\Livrabile\Cursuri\Trimiteri;

use Illuminate\Contracts\Validation\Rule;

class AtLeastOneCustomer implements Rule {

    public $input = NULL;
    public $action = NULL;

    public function __construct($action, $input) {
        $this->action = $action;
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
        return 'Pentru trimitere, trebuie selectat cel puțin un cont client.';
    }
}
