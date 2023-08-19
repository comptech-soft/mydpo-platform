<?php

namespace MyDpo\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer\Accounts\Account;

// use MyDpo\Models\Authentication\User;

class UsersAccountsController extends Controller {
    
    public function index(Request $r) {

        Account::SyncRecords();

        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/authentication/accounts/index.js'],
        );        
    }

    public function getUsers(Request $r) {

        return Account::GetUsers($r->all());
    }

}

// use App\Http\Controllers\Controller;
// use MyDpo\Helpers\Response;
// use MyDpo\Models\Customer\CustomerAccount;



// class PersonsController extends Controller {
    
//     public function index() {
//         

//         return Response::View(
//             '~templates.index', 
//             asset('apps/persons/index.js')
//         );
//     }

// }