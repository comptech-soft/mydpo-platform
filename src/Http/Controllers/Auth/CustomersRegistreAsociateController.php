<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\CustomerRegistruAsociat;

class CustomersRegistreAsociateController extends Controller {
    
    public function getItems(Request $r) {
        return CustomerRegistruAsociat::getItems($r->all());
    }

    public function saveAsociere(Request $r) {
        return CustomerRegistruAsociat::saveAsociere($r->all());
    }

}