<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\RegistruAsociat;

class RegistreController extends Controller {
    
    public function getItems(Request $r) {
        return RegistruAsociat::getItems($r->all());
    }

}