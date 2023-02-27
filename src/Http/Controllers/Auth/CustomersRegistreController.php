<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\CustomerRegister;

class CustomersRegistreController extends Controller {
    
    public function index($customer_id, Request $r) {

        return Response::View(
            '~templates.index', 
            asset('apps/customer-registre/index.js'),
            [], 
            [
                'customer_id' => $customer_id,
            ]
        );
    }

    public function getItems(Request $r) {
        return CustomerRegister::getItems($r->all());
    }

}