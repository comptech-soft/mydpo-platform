<?php

namespace MyDpo\Http\Controllers\Customer\Dashboard\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer\Customer;

class SfaturiDpoController extends Controller {
    
    public function index($customer_id, Request $r) {
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/customer/documents/index.js'],
            payload: [
                'type' => 'sfaturidpo',
                'customer_id' => $customer_id,
                'customer' => Customer::find($customer_id),
            ],
        );        
    }

}