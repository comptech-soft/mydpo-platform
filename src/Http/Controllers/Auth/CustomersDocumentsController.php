<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\Customer;

class CustomersDocumentsController extends Controller {
    
    public function index($customer_id,Request $r) {

        return Response::View(
            '~templates.index', 
            asset('apps/customer-documents/index.js'),
            [], 
            [
                'customer_id' => $customer_id,
            ]
        );
    }

}