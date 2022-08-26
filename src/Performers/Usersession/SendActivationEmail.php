<?php

namespace MyDpo\Performers\Usersession;

use MyDpo\Helpers\Perform;

class SendActivationEmail extends Perform {

    public function Action() {
        
        if(request()->user()->hasVerifiedEmail()) 
        {
            throw new \Exception('Emailul tău este deja verificat și validat.');
        }

        request()->user()->sendEmailVerificationNotification();
    }

} 