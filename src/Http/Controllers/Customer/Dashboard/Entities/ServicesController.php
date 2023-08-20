<?php

namespace MyDpo\Http\Controllers\Customer\Dashboard\Entities;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer\Customer;
// use MyDpo\Models\Customer\Contracts\Order;

class ServicesController extends Controller {

    public function index($customer_id, Request $r) {
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/customer/services/index.js'],
            payload: [
                'customer_id' => $customer_id,
                'customer' => Customer::find($customer_id),
            ],
        );        
    }
       
    // public function getRecords(Request $r) {
    //     return Order::getRecords($r->all());
    // }

    // public function doAction($action, Request $r) {
    //     return CustomerOrder::doAction($action, $r->all());
    // }

}