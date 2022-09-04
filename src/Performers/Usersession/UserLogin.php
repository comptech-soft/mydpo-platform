<?php

namespace MyDpo\Performers\Usersession;

use MyDpo\Helpers\Perform;
use Illuminate\Auth\Events\Login;
use MyDpo\Helpers\UserSession;

class UserLogin extends Perform {
    
    public function Action() {

        $credentials = [
            'email' => $this->input['email'],
            'password' => $this->input['password'],
        ];

        if(! ($attempt = \Auth::attempt($credentials, $this->input['remember_me'])) )
        {
            throw new \Exception('Datele de autentificare nu se potrivesc cu înregistrările noastre.');
        } 

        /**
         * Vad ce roluri poate avea operatorul ==> $slugs
         */
        $user = \Auth::user();

        if( ! $user->role )
        {
            UserSession::logout();
            throw new \Exception('Datele de autentificare nu se potrivesc cu înregistrările noastre.');
        }

        if(config('app.platform') == 'b2b')
        {
            $slugs = ['master', 'customer'];
        }
        else
        {
            $slugs = ['sa', 'admin', 'operator'];
        }

        /**
         * Daca nu are ==> nu face login
         */
        if( ! $user->inRoles($slugs) ) 
        {
            UserSession::logout();
            throw new \Exception('Datele de autentificare nu se potrivesc cu înregistrările noastre.');
        }

        request()->session()->regenerate();

        $this->payload = $attempt;

        event(new Login(
            \Auth::guard(), 
            \Auth::user(),
            $this->input['remember_me']
        ));

    }

} 