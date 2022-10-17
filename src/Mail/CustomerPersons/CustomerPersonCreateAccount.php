<?php

namespace MyDpo\Mail\CustomerPersons;

use MyDpo\Mail\BaseEmail;
use MyDpo\Models\Activation;
use MyDpo\Models\Platform;

class CustomerPersonCreateAccount extends BaseEmail {
    
    public function __construct($entity, $action, $input) {

        parent::__construct($entity, $action, $input);

        $activation = Activation::createActivation( $this->input['account']->user_id);
        $platform = Platform::where('slug', 'b2b')->first();

        dd($platform);

        $this->actionUrl = \Str::replace(
            '[token]', 
            $activation->token, 
            $this->email_template->props['actionUrl']
        );
    }
}
