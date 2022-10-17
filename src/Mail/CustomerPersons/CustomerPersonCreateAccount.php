<?php

namespace MyDpo\Mail\CustomerPersons;

use MyDpo\Mail\BaseEmail;

class CustomerPersonCreateAccount extends BaseEmail {
    
    public function __construct($entity, $action, $input) {

        parent::__construct($entity, $action, $input);

        $this->email_template->props['actionUrl'] = \Str::replace(
            '[user_id]', 
            $this->input['account']->user_id, 
            $this->email_template->props['actionUrl']
        );

        dd($this->email_template->props['actionUrl']);
    }
}
