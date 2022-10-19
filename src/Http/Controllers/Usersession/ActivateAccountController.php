<?php

namespace MyDpo\Http\Controllers\Usersession;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Helpers\UserSession;
use MyDpo\Models\Activation;

class ActivateAccountController extends Controller {

    public function index($token, Request $r) {

        $activation = Activation::byToken($token);

        if($activation && ($activation->activated == 0) )
        {
            return Response::View(
                '~templates.index', 
                asset('apps/activate-account/index.js'),
                [], 
                [
                    'token' => $token,
                ]
            );
        }
        
        return redirect(route('dashboard'));
    }

    public function activateAccount(Request $r) {
        return UserSession::activateAccount($r->only([
            'email', 
            'token', 
            'password', 
            'password_confirmation'
        ]));
    }

    public function getInfosByToken(Request $r) {
        return UserSession::getInfosByToken($r->only([
            'token', 
        ]));
    }
    

}