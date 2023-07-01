<?php

namespace MyDpo\Http\Controllers\Admin\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer;

class CustomerChestionareController extends Controller {
    
    public function index($customer_id, Request $r) {
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/customer/chestionare/index.js'],
            payload: [
                'customer_id' => $customer_id,
                'customer' => Customer::find($customer_id),
            ],
        );        
    }

    // public function index($customer_id, Request $r) {

    //     return Response::View(
    //         '~templates.index', 
    //         asset('apps/customer-chestionare/index.js'),
    //         [], 
    //         [
    //             'customer_id' => $customer_id,
    //         ]
    //     );
    // }

    // public function getItems(Request $r) {
    //     return CustomerChestionar::getItems($r->all());
    // }

    // public function getSummary(Request $r) {
    //     return CustomerChestionar::getSummary($r->all());
    // }
    
}