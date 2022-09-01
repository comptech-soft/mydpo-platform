<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\CustomerContract;

class CustomersContracteController extends Controller
{
    
    public function getItems(Request $r) {
        return CustomerContract::getItems($r->all());
    }
    
    public function doAction($action, Request $r) {
        return CustomerContract::doAction($action, $r->all());
    }

}