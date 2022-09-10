<?php

namespace MyDpo\Performers\Usersession;

use MyDpo\Helpers\Perform;
use Illuminate\Support\Facades\Password;

class SendResetPasswordLink extends Perform {

    public function Action() {
        
        $status = Password::sendResetLink($this->input);

        if( $status != Password::RESET_LINK_SENT )
        {
            throw new \Exception( __($status) );
        }

    }

} 