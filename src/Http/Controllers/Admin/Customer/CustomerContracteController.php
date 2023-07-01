<?php

namespace MyDpo\Http\Controllers\Admin\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer;

class CustomerContracteController extends Controller {
    
    public function index($customer_id, Request $r) {
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/customer/contracte/index.js'],
            payload: [
                'customer_id' => $customer_id,
                'customer' => Customer::find($customer_id),
            ],
        );        
    }

    // public function index($customer_id, Request $r) {

    //     return Response::View(
    //         '~templates.index', 
    //         asset('apps/customer-contracte/index.js'),
    //         [], 
    //         [
    //             'customer_id' => $customer_id,
    //             'insert' => $r->insert,
    //         ]
    //     );
    // }

    // public function getItems(Request $r) {
    //     return CustomerContract::getItems($r->all());
    // }
    
    // public function doAction($action, Request $r) {
    //     return CustomerContract::doAction($action, $r->all());
    // }

}