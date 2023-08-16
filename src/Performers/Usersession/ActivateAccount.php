<?php

namespace MyDpo\Performers\Usersession;

use MyDpo\Helpers\Perform;
use Illuminate\Support\Facades\Password;
use MyDpo\Models\Activation;
use MyDpo\Models\User;
use MyDpo\Models\Customer\Customer;
use MyDpo\Models\Authentication\UserSetting;
use Illuminate\Auth\Events\Login;

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

        if($this->input['password'])
        {
            $user->forceFill([
                'password' => \Hash::make($this->input['password']),
                'remember_token' => \Str::random(60),
                'email_verified_at' => $now = \Carbon\Carbon::now(),
                'activated_at' => $now,
            ])->save();
        }
        else
        {
            $user->forceFill([
                'email_verified_at' => $now = \Carbon\Carbon::now(),
                'activated_at' => $now,
            ])->save();
        }

        $activation->update([
            'activated' => 1,
            'activated_at' => $now,
        ]);

        \Auth::login($user);

        event(new Login(
            \Auth::guard(), 
            \Auth::user(),
            false,
        ));

        UserSetting::saveActiveCustomer([
            'user_id' => $user->id,
            'platform' => config('app.platform'),
            'customer_id' => $customer->id,
        ]);

    }

   
}