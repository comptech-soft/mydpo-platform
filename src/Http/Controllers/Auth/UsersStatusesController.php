<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserStatus;

class UsersStatusesController extends Controller {
    
    public function getItems(Request $r) {
        return UserStatus::getItems($r->all());
    }

}