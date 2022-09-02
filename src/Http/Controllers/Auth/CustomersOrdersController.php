<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\CustomerOrder;

class CustomersOrdersController extends Controller
{
    
    public function getItems(Request $r) {
        return CustomerOrder::getItems($r->all());
    }
    
    public function doAction($action, Request $r) {
        return CustomerOrder::doAction($action, $r->all());
    }

}