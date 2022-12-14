<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\CustomerContract;

class CustomersContracteController extends Controller {
    
    public function index($customer_id,Request $r) {

        return Response::View(
            '~templates.index', 
            asset('apps/customer-contracte/index.js'),
            [], 
            [
                'customer_id' => $customer_id,
                'insert' => $r->insert,
            ]
        );
    }

    public function getItems(Request $r) {
        return CustomerContract::getItems($r->all());
    }
    
    public function doAction($action, Request $r) {
        return CustomerContract::doAction($action, $r->all());
    }

}