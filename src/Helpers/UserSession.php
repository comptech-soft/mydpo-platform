<?php

namespace MyDpo\Helpers;

use Illuminate\Validation\Rules;
use MyDpo\Performers\Usersession\Logout;
use MyDpo\Performers\Usersession\UserLogin;
use MyDpo\Performers\Usersession\SendResetPasswordLink;
use MyDpo\Performers\Usersession\UpdateNewPassword;
// use Comptech\Performers\System\Register;
use MyDpo\Performers\Usersession\SendActivationEmail;
use MyDpo\Performers\Usersession\ActivateAccount;
use MyDpo\Performers\Usersession\GetInfosByToken;

class UserSession {

    public static function logout() {

        return 
            (new Logout(NULL))
            ->SetSuccessMessage(NULL)
            ->SetExceptionMessage([
                \Exception::class => NULL,
            ])
            ->Perform();
    }

    public static function register($input) {       
        $rules = [
            'first_name' => [
                'required', 
                'string', 
                'max:191'
            ],
            'last_name' =>  [
                'required', 
                'string', 
                'max:191'
            ],
            'email' => [
                'required', 
                'string', 
                'email', 
                'max:191', 
                'unique:users'
            ],
            'password' => [
                'required', 
                'confirmed',
                'min:8', 
                Rules\Password::defaults()
            ],
            'agree' => 'accepted',
            'role_id' => 'required|exists:roles,id',
            'activate' => 'integer|in:0,1',
        ];

        if($input['activate'] == 1)
        {
            return 
                (new RegisterAndActivate($input, $rules))
                ->SetSuccessMessage(['Contul a fost creat și activat cu succes.'])
                ->SetExceptionMessage([
                    \Exception::class => NULL
                ])
                ->Perform();
        }
        return 
            (new Register($input, $rules))
            ->SetSuccessMessage(['Contul a fost creat cu succes.'])
            ->SetExceptionMessage([
                \Exception::class => NULL
            ])
            ->Perform();            
    }

    public static function login($input) {
        return 
            (new UserLogin(
                $input, 
                [
                    'email' => 'required|email',
                    'password' => 'required',
                ],
                [
                    'email.required' => 'Adresa de email este obligatorie.',
                    'email.email' => 'Adresa de email pare că nu este validă.',
                    'password.required' => 'Parola este obligatorie.',
                ]
            ))
            ->SetSuccessMessage(NULL)
            ->SetExceptionMessage([
                \Exception::class => NULL,
            ])
            ->Perform();
    }

    public static function sendResetPasswordLink($input) {
        return 
            (new SendResetPasswordLink(
                $input, 
            [
                'email' => 'required|email|exists:users,email',
            ],
            [
                'email.required' => 'Adresa de email este obligatorie.',
                'email.email' => 'Adresa de email pare că nu este validă.',
                'email.exists' => 'Adresa de email nu este înregistrată la noi.',
            ]
            ))
            ->SetSuccessMessage('Emailul a fost trimis cu succes.')
            ->SetExceptionMessage([
                \Exception::class => NULL,
            ])
            ->Perform();
    }

    public static function updateNewPassword($input) {
        
        return (new UpdateNewPassword(
            $input, 
            [
                'password' => [
                    'required', 
                    'confirmed', 
                    Rules\Password::defaults()->mixedCase()->letters()->numbers()->symbols()
                ],
                'token' => 'required',
                'email' => 'required|email|exists:users,email',
            ],
            [
                'token.required' => 'Codul de resetare este obligatoriu.',
                'password.min' => 'Parola trebuie să fie de cel puțin 8 caractere și să conțină litere mari și mici, cifre și caractere speciale.',
            ]
        ))
        ->SetSuccessMessage('Parola a fost schimbată cu success!')
        ->SetExceptionMessage([
            \Exception::class => NULL,
        ])
        ->Perform();
    }

    public static function activateAccount($input) {
       
        dd($input);
        
        return (new ActivateAccount(
            $input, 
            [
                'password' => [
                    'required', 
                    'confirmed', 
                    Rules\Password::defaults()->mixedCase()->letters()->numbers()->symbols()
                ],
                'token' => 'required',
                'email' => 'required|email|exists:users,email',
            ],
            [
                'token.required' => 'Codul de resetare este obligatoriu.',
                'password.min' => 'Parola trebuie să fie de cel puțin 8 caractere și să conțină litere mari și mici, cifre și caractere speciale.',
            ]
        ))
        ->SetSuccessMessage('Parola a fost schimbată cu success!')
        ->SetExceptionMessage([
            \Exception::class => NULL,
        ])
        ->Perform();
    }

    public static function sendActivationEmail() {
        return 
            (new SendActivationEmail(
                NULL, 
                [
                ],
                [
                ]
            ))
            ->SetSuccessMessage('Emailul a fost trimis cu succes.')
            ->SetExceptionMessage([
                \Exception::class => NULL,
            ])
            ->Perform();
    }

    public static function getInfosByToken($input) {
        return 
            (new GetInfosByToken(
                $input, 
                [
                ],
                [
                ]
            ))
            ->SetExceptionMessage([
                \Exception::class => NULL,
            ])
            ->Perform();
    }

    

    
}