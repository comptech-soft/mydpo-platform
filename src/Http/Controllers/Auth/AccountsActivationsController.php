<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\Activation;

class AccountsActivationsController extends Controller {
    
    public function getItems(Request $r) {
        return Activation::getItems($r->all());
    }

}