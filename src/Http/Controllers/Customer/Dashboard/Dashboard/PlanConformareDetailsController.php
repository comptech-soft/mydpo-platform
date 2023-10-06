<?php

namespace MyDpo\Http\Controllers\Customer\Dashboard\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer\Customer;
use MyDpo\Models\Customer\Planuriconformare\Planconformare;

class PlanConformareDetailsController extends Controller {
    
    public function index($customer_id, $plan_id, Request $r) {

        if( ! ($plan = Planconformare::where('id', $plan_id)->without(['department'])->first()) )
        {
            return redirect()->back();
        }

        $customer = Customer::find($customer_id);
               
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/customer/plan-conformare-details/index.js'],
            payload: [
                'plan_id' => $plan_id,
                'plan' => $plan,
                'customer' => $customer,
                'rows' => $plan->GetRowsAsTable(),
                'tree' => $plan->GetTree(),
                // 'customer_user' => \Auth::user(),
            ],
        );        
    }

    

}