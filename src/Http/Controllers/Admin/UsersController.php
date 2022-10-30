<?php

namespace MyDpo\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\User;

class UsersController extends Controller {
    
    public function index(Request $r) {
        return Response::View(
            '~templates.index', 
            asset('apps/users/index.js')
        );
    }

    public function getItems(Request $r) {
        return User::getItems($r->all());
    }

    public function doAction($action, Request $r) {
        return User::doAction($action, $r->all());
    }

    public function updatePassword(Request $r) {
        return User::updatePassword($r->all());
    }

    public function updatePermissions(Request $r) {
        return User::updatePermissions($r->all());
    }
    

    
}