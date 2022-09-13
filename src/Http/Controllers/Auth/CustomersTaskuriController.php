<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;

class CustomersTaskuriController extends Controller {
    
    public function index($customer_id,Request $r) {

        return Response::View(
            '~templates.index', 
            asset('apps/customer-taskuri/index.js'),
            [], 
            [
                'customer_id' => $customer_id,
            ]
        );
    }

  
}