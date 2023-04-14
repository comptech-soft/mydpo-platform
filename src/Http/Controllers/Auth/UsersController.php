<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller {
    
    public function getItems(Request $r) {
        return User::getItems($r->all());
    }

    public function doAction($action, Request $r) {
        return User::doAction($action, $r->all());
    }

    public function changePassword(Request $r) {
        return User::changePassword($r->all());
    }

    public function saveUserSettings(Request $r) {
        return User::saveUserSettings($r->all());
    }

}