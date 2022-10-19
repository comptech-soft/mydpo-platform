<?php

namespace MyDpo\Performers\Usersession;

use MyDpo\Helpers\Perform;
// use Illuminate\Support\Facades\Password;
use MyDpo\Models\Activation;
// use MyDpo\Models\User;
// use MyDpo\Models\Customer;

class GetInfosByToken extends Perform {


    public function Action() {
        
        $activation = Activation::byToken($this->input['token']);


        $this->payload = [
            'activation' => $activation,
            'customer' => $activation->customer,
        ];

    }

   
} 