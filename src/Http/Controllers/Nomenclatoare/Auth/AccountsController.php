<?php

namespace MyDpo\Http\Controllers\Nomenclatoare\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer\Accounts\Account;

class AccountsController extends Controller {
    
    public function index(Request $r) {

        Account::SyncRecords();

        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/nomenclatoare/auth/user-accounts/index.js'],
        );        
    }

    public function getUsers(Request $r) {
        return Account::GetUsers($r->all());
    }

}