<?php

namespace MyDpo\Http\Controllers\Admin\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer;

class CustomerCentralizatorController extends Controller {
    
    public function index($customer_id, $centralizator_id, Request $r) {

        dd(__METHOD__, $centralizator_id);

        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/customer/centralizatoare/index.js'],
            payload: [
                'customer_id' => $customer_id,
                'centralizator_id' => $centralizator_id,
                'customer' => Customer::find($customer_id),
            ],
        );        
    }

}