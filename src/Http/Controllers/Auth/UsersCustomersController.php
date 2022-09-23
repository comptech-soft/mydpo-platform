<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\UserCustomer;

class UsersCustomersController extends Controller {
    
    public function getItems(Request $r) {
        return UserCustomer::getItems($r->all());
    }

}