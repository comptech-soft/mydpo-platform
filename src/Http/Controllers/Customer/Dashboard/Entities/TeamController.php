<?php

namespace MyDpo\Http\Controllers\Customer\Dashboard\Entities;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer\Customer;
use MyDpo\Models\Customer\Customer\CustomerTeam;

class CustomerTeamController extends Controller {
    
    public function index($customer_id, Request $r) {
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/customer/team/index.js'],
            payload: [
                'customer_id' => $customer_id,
                'customer' => Customer::find($customer_id),
            ],
        );        
    }

    public function getRecords(Request $r) {
        return CustomerTeam::getRecords($r->all());
    }
    
    // public function doAction($action, Request $r) {
    //     return UserCustomer::doAction($action, $r->all());
    // }

  
}