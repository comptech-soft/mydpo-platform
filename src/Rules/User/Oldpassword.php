<?php

namespace MyDpo\Rules\User;

use Illuminate\Contracts\Validation\Rule;

class Oldpassword implements Rule {

    public $input = NULL;
    public $record = NULL;
    public $message = NULL;

    public function __construct($input) {
        $this->input = $input;
    }

    public function passes($attribute, $value) {   
        
        dd($this->input);
    }

    public function message() {
        return $this->message;
    }
}

// if(!Hash::check($request->old_password, auth()->user()->password)){
//     return back()->with("error", "Old Password Doesn't match!");
// }