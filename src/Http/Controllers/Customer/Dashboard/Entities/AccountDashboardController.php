<?php

namespace MyDpo\Http\Controllers\Customer\Dashboard\Entities;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
// use MyDpo\Models\Customer\Accounts\Account;

class AccountDashboardController extends Controller {
    
    public function index($account_id, Request $r) {

        dd($account_id);
        
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/customer/conturi/index.js'],
            payload: [
                'customer_id' => $customer_id,
                'customer' => Customer::find($customer_id),
                'customer_user' => \Auth::user(),
            ],
        );        
    }



}