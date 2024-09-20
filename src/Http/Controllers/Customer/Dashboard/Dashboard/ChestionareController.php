<?php

namespace MyDpo\Http\Controllers\Customer\Dashboard\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer\Customer;
use MyDpo\Models\Customer\Chestionare\CustomerChestionar;

class ChestionareController extends Controller {
    
    public function index($customer_id, Request $r) {

        dd(__METHOD__);
        
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/customer/chestionare/index.js'],
            payload: [
                'customer_id' => $customer_id,
                'customer' => Customer::find($customer_id),
                'customer_user' => \Auth::user(),
            ],
        );        
    }

    public function getRecords(Request $r) {
        return CustomerChestionar::getRecords($r->all());
    }

    public function doAction($action, Request $r) {
        return CustomerChestionar::doAction($action, $r->all());
    }
    
}