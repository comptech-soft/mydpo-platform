<?php

namespace MyDpo\Rules\User;

use Illuminate\Contracts\Validation\Rule;
use MyDpo\Models\Authentication\User;

class Oldpassword implements Rule {

    public $input = NULL;
    public $record = NULL;
    public $message = NULL;

    public function __construct($input) {
        $this->input = $input;
    }

    public function passes($attribute, $value) {   
        
        $user = User::find($this->input['id']);

        return \Hash::check(
            $this->input['oldpassword'], 
            $user->password
        );

    }

    public function message() {
        return 'Se pare că parola actuală nu este introdusă corect';
    }
}

