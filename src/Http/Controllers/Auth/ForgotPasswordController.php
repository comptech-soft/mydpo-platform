<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Helpers\UserSession;

class ForgotPasswordController extends Controller {

    public function index(Request $r) {
        return Response::View(
            '~templates.index', 
            asset('apps/forgot-password/index.js')
        );
    }

    public function sendResetPasswordLink(Request $r) {
        return UserSession::sendResetPasswordLink($r->only([
            'email'
        ]));
    }

}