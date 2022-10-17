<?php

namespace MyDpo\Mail\CustomerPersons;

use MyDpo\Mail\BaseEmail;
use MyDpo\Models\Activation;

class CustomerPersonCreateAccount extends BaseEmail {
    
    public function __construct($entity, $action, $input) {

        parent::__construct($entity, $action, $input);

        $activation = Activation::createActivation([
            'user_id' => $this->input['account']->user_id,
        ]);

        dd($activation);

        $this->actionUrl = \Str::replace(
            '[user_id]', 
            $this->input['account']->user_id, 
            $this->email_template->props['actionUrl']
        );
    }
}
