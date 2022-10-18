<?php

namespace MyDpo\Performers\Usersession;

use MyDpo\Helpers\Perform;
use Illuminate\Support\Facades\Password;
use MyDpo\Models\Activation;
use MyDpo\Models\User;
use MyDpo\Models\Customer;

class ActivateAccount extends Perform {


    public function Action() {
        
        $activation = Activation::byToken($this->input['token']);

        if(! $activation )
        {
            throw new \Exception('Nu există cerere de activare a contului pentru acest token sau este deja completată.');
        }

        if($activation->activated == 1)
        {
            throw new \Exception('Cererea de activare a contului este deja completată.');
        }

        $user = User::byEmail($this->input['email']);

        if(! $user || ($user->id != $activation->user_id))
        {
            throw new \Exception('Nu există utilizatorul corespunzător acestei cereri de activare a contului.');
        }

        $customer = Customer::find($activation->customer_id);

        if(! $customer )
        {
            throw new \Exception('Nu există clientul corespunzător acestei cereri de activare a contului.');
        }

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
                    'email_verified_at' => \Carbon\Carbon::now(),
                ])->save();
                // event(new PasswordReset($user));
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