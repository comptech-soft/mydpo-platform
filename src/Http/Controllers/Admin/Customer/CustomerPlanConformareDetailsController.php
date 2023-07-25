<?php

namespace MyDpo\Http\Controllers\Admin\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer;
use MyDpo\Models\Customer\CustomerPlanconformare;
// use MyDpo\Models\Customer\CustomerPlanconformareRow;

class CustomerPlanConformareDetailsController extends Controller {
    
    public function index($plan_id, Request $r) {

        $plan = CustomerPlanconformare::where('id', $plan_id)->with(['rows.children'])->first();

        if( ! $plan )
        {
            return redirect()->back();
        }

        $customer = Customer::find($plan->customer_id);
        // $rows = CustomerPlanconformareRow::where('customer_plan_id', $plan_id)->
        
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/customer/plan-conformare-details/index.js'],
            payload: [
                'plan_id' => $plan_id,
                'plan' => $plan,
                'customer' => $customer,
                // 'rows' => $plan->rows,
            ],
        );        
    }

    

}