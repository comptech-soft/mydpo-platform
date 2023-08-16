<?php

namespace MyDpo\Rules\CustomerAccount;

use Illuminate\Contracts\Validation\Rule;
use MyDpo\Models\CustomerAccount;
use MyDpo\Models\Authentication\User;

class ValidAccountEmail implements Rule {

    public $input = NULL;
    public $record = NULL;
    public $message = NULL;

    public function __construct($input) {
        $this->input = $input;
    }

    public function passes($attribute, $value) {   

    
        $exists = User::where('email', $email = $this->input['user']['email'])->first();

        if($exists)
        {
            $this->message = 'Adresa de email este deja folosită';
            return FALSE;
        }

        if( config('app.platform') == 'b2b')
        {
            $domain_name_1 = substr(strrchr($email, "@"), 1);
            $domain_name_2 = substr(strrchr(\Auth::user()->email, "@"), 1);

            if($domain_name_1 != $domain_name_2)
            {
                $this->message = 'Adresa de email trebuie să aibă domeniul [' .  $domain_name_2 . ']';
                return FALSE;
            }
        }
        
        return TRUE;
    }

    public function message()
    {
        return $this->message;
    }
}
