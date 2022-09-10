<?php

namespace MyDpo\Performers\Usersession;

use MyDpo\Helpers\Perform;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

class UpdateNewPassword extends Perform {


    public function Action() {
        
        /**
         * Here we will attempt to reset the user's password. If it is successful we
         * will update the password on an actual user model and persist it to the
         * database. Otherwise we will parse the error and return the response.
         */
        $input = $this->input;

        $status = Password::reset(
            $input,
            function ($user) use ($input) {
                $user->forceFill([
                    'password' => \Hash::make($input['password']),
                    'remember_token' => \Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        /**
         * If the password was successfully reset, we will redirect the user back to
         * the application's home authenticated view. If there is an error we can
         * redirect them back to where they came from with their error message.
         */
        if($status != Password::PASSWORD_RESET)
        {
            throw new \Exception( __($status) );
        }

    }

   
} 