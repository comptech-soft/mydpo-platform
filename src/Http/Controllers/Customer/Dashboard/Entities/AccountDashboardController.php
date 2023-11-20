<?php

namespace MyDpo\Http\Controllers\Customer\Dashboard\Entities;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer\Accounts\Account;

class AccountDashboardController extends Controller {
    
    public function index($customer_id, $account_id, Request $r) {

        $account = Account::find($account_id);

        if(! $account || $account->customer_id != $customer_id)
        {
            return redirect('/');
        }

        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/customer/cont-dashboard/index.js'],
            payload: [
                'customer_id' => $account->customer_id,
                'customer' => $account->customer->toArray(),
                'account' => $account->toArray(),
                'customer_user' => \Auth::user(),
            ],
        );        
    }



}