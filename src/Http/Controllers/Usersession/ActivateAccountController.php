<?php

namespace MyDpo\Http\Controllers\Usersession;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Helpers\UserSession;

class ActivateAccountController extends Controller {

    public function index($token, Request $r) {
        return Response::View(
            '~templates.index', 
            asset('apps/activate-account/index.js'),
            [], 
            [
                'token' => $token,
            ]
        );
    }

    public function activateAccount(Request $r) {
        return UserSession::activateAccount($r->only([
            'email', 
            'token', 
            'password', 
            'password_confirmation'
        ]));
    }

}