<?php

namespace MyDpo\Http\Controllers\Customer\Dashboard\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer\Customer;

class DpiaToolController extends Controller {
    
    public function index($customer_id, Request $r) {
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/customer/dpia-tool/index.js'],
            payload: [
                'customer_id' => $customer_id,
                'customer' => Customer::find($customer_id),
            ],
        );        
    }

    // public function index($customer_id, Request $r) {

    //     return Response::View(
    //         '~templates.index', 
    //         asset('apps/customer-dpia-tool/index.js'),
    //         [], 
    //         [
    //             'customer_id' => $customer_id,
    //         ]
    //     );
    // }

}