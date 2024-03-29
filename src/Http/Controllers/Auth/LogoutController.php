<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\UserSession;

class LogoutController extends Controller {

    public function logout() {
        return UserSession::logout();
    }

}