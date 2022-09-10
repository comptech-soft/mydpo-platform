<?php

namespace Comptech\Http\Controllers\Usersession;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Comptech\Helpers\Response;
// use Comptech\System\UserSession;

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