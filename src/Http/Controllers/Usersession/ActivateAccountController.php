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
            asset('apps/activate-accont/index.js'),
            [], 
            [
                'token' => $token,
            ]
        );
    }

    public function sendResetPasswordLink(Request $r) {
        return UserSession::sendResetPasswordLink($r->only([
            'email'
        ]));
    }

}