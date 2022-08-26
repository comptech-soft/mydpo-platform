<?php

namespace MyDpo\Helpers;

use Illuminate\Validation\Rules;

use MyDpo\Performers\Usersession\Logout;
use MyDpo\Performers\Usersession\UserLogin;
// use Comptech\Performers\System\SendResetPasswordLink;
// use Comptech\Performers\System\UpdateNewPassword;
// use Comptech\Performers\System\Register;
use MyDpo\Performers\Usersession\SendActivationEmail;

// use Comptech\Rules\User\ValidPassword;
// use Comptech\Rules\User\UpdatedPassword;

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
                'password' => new ValidPassword($input),
                'password_confirmation' => new UpdatedPassword($input),
                'token' => 'required',
                'email' => 'required|email|exists:users,email',
            ],
            [
                'token.required' => 'Codul de resetare este obligatoriu.',
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

    
}