<?php

namespace MyDpo\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer;
use MyDpo\Models\Customer\CustomerEmail;

class CustomerEmailsController extends Controller {

    public function index($customer_id, Request $r) {
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/customer/emails/index.js'],
            payload: [
                'customer_id' => $customer_id,
                'customer' => Customer::find($customer_id),
            ],
        );        
    }

    public function getRecords(Request $r) {
        return CustomerEmail::getRecords($r->all());
    }
  
}