<?php

namespace MyDpo\Http\Controllers\Customer\Dashboard\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer\Customer;
use MyDpo\Models\Customer\ELearning\CustomerCurs;

class CursurilemeleController extends Controller {

    public function index($customer_id, Request $r) {

        CustomerCurs::Sync($customer_id);

        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/customer/cursurile-mele/index.js'],
            payload: [
                'customer_id' => $customer_id,
                'customer' => Customer::find($customer_id),
                'customer_user' => \Auth::user(),
            ],
        );        
    }

}