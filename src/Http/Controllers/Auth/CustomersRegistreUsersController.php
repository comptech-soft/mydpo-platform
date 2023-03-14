<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\CustomerRegistruUser;

class CustomersRegistreUsersController extends Controller {

    public function getItems(Request $r) {
        return CustomerRegistruUser::getItems($r->all());
    }

}