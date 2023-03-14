<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\CustomerRegisterUser;

class CustomersRegistreUsersController extends Controller {

    public function getItems(Request $r) {
        return CustomerRegisterUser::getItems($r->all());
    }

}