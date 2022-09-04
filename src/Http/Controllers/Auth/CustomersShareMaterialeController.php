<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\Sharematerial;

class CustomersShareMaterialeController extends Controller
{
    
    public function getItems(Request $r) {
        return Sharematerial::getItems($r->all());
    }

}