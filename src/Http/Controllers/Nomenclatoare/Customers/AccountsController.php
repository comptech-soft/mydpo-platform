<?php

namespace MyDpo\Http\Controllers\Nomenclatoare\Customers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer\Accounts\Account;

class AccountsController extends Controller {
    
    public function index(Request $r) {

        Account::SyncRecords();

        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/nomenclatoare/customers/customer-accounts/index.js'],
        );        
    }

    public function getCustomers(Request $r) {
        return Account::GetCustomers($r->all());
    }

}