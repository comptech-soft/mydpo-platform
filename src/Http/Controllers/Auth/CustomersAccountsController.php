<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\CustomerAccount;

class CustomersAccountsController extends Controller
{
    
    public function getItems(Request $r) {
        return CustomerAccount::getItems($r->all());
    }
    
    public function doAction($action, Request $r) {
        return CustomerAccount::doAction($action, $r->all());
    }

}