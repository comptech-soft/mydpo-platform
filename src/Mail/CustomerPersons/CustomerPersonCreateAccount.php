<?php

namespace MyDpo\Mail\CustomerPersons;

use MyDpo\Mail\BaseEmail;

class CustomerPersonCreateAccount extends BaseEmail {
    
    public function __construct($entity, $action, $input) {

        parent::__construct($entity, $action, $input);

        dd($this->email_template->props['actionUrl'], $this->input['account']->user_id);
    }
}
