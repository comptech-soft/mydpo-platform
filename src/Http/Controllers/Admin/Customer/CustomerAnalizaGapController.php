<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer;

class CustomerAnalizaGapController extends Controller {
    
    public function index($customer_id, Request $r) {
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/customer/analiza-gap/index.js'],
            payload: [
                'customer_id' => $customer_id,
                'customer' => Customer::find($customer_id),
            ],
        );        
    }

    // public function index($customer_id, Request $r) {

    //     return Response::View(
    //         '~templates.index', 
    //         asset('apps/customer-analiza-gap/index.js'),
    //         [], 
    //         [
    //             'customer_id' => $customer_id,
    //         ]
    //     );
    // }

}