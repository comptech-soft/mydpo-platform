<?php

namespace MyDpo\Performers\Customer\Account;

use MyDpo\Helpers\Perform;
use MyDpo\Models\Customer\Accounts\Account;
use MyDpo\Models\Authentication\User;

class GetUsers extends Perform {

    public function Action() {

        $users = User::whereIn('id', Account::distinct()->pluck('user_id'))->get();


        $this->payload = [
            'users' => $users,
        ];
    }
       
}