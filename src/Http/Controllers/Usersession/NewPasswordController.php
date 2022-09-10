<?php

namespace MyDpo\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Comptech\Helpers\Response;
use Comptech\System\UserSession;

class NewPasswordController extends Controller
{
    public function index($token, Request $r) {
        return Response::View(
            '~templates.index', 
            asset('apps/new-password/index.js'), 
            [], 
            [
                'token' => $token,
                'email' => $r->email, 
            ]
        );
    }

    public function updateNewPassword(Request $r) {
        return UserSession::updateNewPassword($r->only([
            'email', 
            'token', 
            'password', 
            'password_confirmation'
        ]));
    }

}