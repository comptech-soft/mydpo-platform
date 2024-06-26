<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Helpers\UserSession;
use MyDpo\Models\Customer\Accounts\Activation;
use MyDpo\Core\Http\Response\Index;

class ActivateAccountController extends Controller {

    public function index($token, Request $r) {
        
        $activation = Activation::byToken($token);

        if($activation && ($activation->activated == 0) )
        {

            \Session::flush();
 
            request()->session()->invalidate();
            request()->session()->regenerateToken();

            if(config('app.platform') == 'admin')
            {
                return Index::View(
                    styles: ['css/app.css'],
                    scripts: ['apps/auth/activate-team/index.js'],
                    payload: [
                        'token' => $token,
                    ],
                ); 
            }

            return Response::View(
                '~templates.index', 
                asset('apps/activate-account/index.js'),
                [], 
                [
                    'token' => $token,
                ]
            );
        }
        
        return redirect('/');
    }

    public function activateAccount(Request $r) {
        return UserSession::activateAccount($r->only([
            'email', 
            'token', 
            'password', 
            'password_confirmation',
            'has_password',
            'agree',
        ]));
    }

    public function getInfosByToken(Request $r) {
        return UserSession::getInfosByToken($r->only(['token']));
    }
    
}