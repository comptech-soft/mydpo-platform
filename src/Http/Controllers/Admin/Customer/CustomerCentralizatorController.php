<?php

namespace MyDpo\Http\Controllers\Admin\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer;
use MyDpo\Models\Livrabile\Centralizator;
use MyDpo\Models\Customer\CustomerCentralizator;

class CustomerCentralizatorController extends Controller {
    
    public function index($customer_id, $centralizator_id, Request $r) {

        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/customer/centralizator/index.js'],
            payload: [
                'customer_id' => $customer_id,
                'centralizator_id' => $centralizator_id,
                'customer' => Customer::find($customer_id),
                'centralizator' => Centralizator::find($customer_id),
            ],
        );        
    }

    public function getRecords(Request $r) {
        return CustomerCentralizator::getRecords($r->all());
    }

}