<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\RoleUser;

class UsersRolesController extends Controller {
    
    public function getItems(Request $r) {
        return RoleUser::getItems($r->all());
    }

}