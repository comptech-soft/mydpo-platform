<?php

namespace MyDpo\Performers\Usersession;

use MyDpo\Helpers\Perform;
use MyDpo\Models\Customer\Accounts\Activation;

class GetInfosByToken extends Perform {

    public function Action() {
        
        $activation = Activation::byToken($this->input['token']);

        $this->payload = [
            'activation' => $activation,
            'customer' => $activation->customer,
            'user' => $activation->user,
        ];

    }

   
} 