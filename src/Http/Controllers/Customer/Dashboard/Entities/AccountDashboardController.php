<?php

namespace MyDpo\Http\Controllers\Customer\Dashboard\Entities;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer\Accounts\Account;
use MyDpo\Models\Customer\Dashboard\Item;

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
                'accounts' => Account::where('user_id', $account->user_id)->where('id', '<>', $account->id)->get()->toArray(),
                'dashboard_items' => Item::getByColumns(),
                'drawer_items' => \MyDpo\Models\System\SysMenu::getMenus('b2b', $account)['DrawerB2B'],
                'actions_items' => \MyDpo\Models\System\SysAction::getActions('b2b', $account),
            ],
        );        
    }

}