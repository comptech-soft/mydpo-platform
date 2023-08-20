<?php

namespace MyDpo\Http\Controllers\Usersession;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Helpers\UserSession;

class LoginController extends Controller
{
    
    public function index(Request $r) {
        return Response::View(
            '~templates.index', 
            asset('apps/login/index.js')
        );
    }

    public function login(Request $r) {
        return UserSession::login($r->only([
            'email', 
            'password', 
            'remember_me'
        ]));
    }

}