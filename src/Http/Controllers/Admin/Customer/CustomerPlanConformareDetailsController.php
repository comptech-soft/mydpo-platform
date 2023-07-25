<?php

namespace MyDpo\Http\Controllers\Admin\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
// use MyDpo\Models\Customer;
// use MyDpo\Models\Customer\CustomerPlanconformare;

class CustomerPlanConformareDetailsController extends Controller {
    
    public function index($plan_id, Request $r) {
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/customer/plan-conformare-details/index.js'],
            payload: [
                // 'customer_id' => $customer_id,
                // 'customer' => Customer::find($customer_id),
            ],
        );        
    }

    

}