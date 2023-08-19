<?php

namespace MyDpo\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer\Accounts\Account;

class UsersAccountsController extends Controller {
    
    public function index(Request $r) {

        Account::SyncRecords();

        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/authentication/user-accounts/index.js'],
        );        
    }

    public function getUsers(Request $r) {
        return Account::GetUsers($r->all());
    }

}