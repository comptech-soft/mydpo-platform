<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\Customer\CustomerService;

class CustomersServicesController extends Controller
{
    
    public function getItems(Request $r) {
        return CustomerService::getItems($r->all());
    }
    
    public function doAction($action, Request $r) {
        return CustomerService::doAction($action, $r->all());
    }

}