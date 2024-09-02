<?php

namespace MyDpo\Http\Controllers\Customer\Dashboard\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer\Customer;
use  MyDpo\Models\Customer\Chestionare\CustomerChestionar;
use  MyDpo\Models\Customer\Chestionare\CustomerChestionarUser;

class ChestionarParcurgereController extends Controller {

    public function index($customer_id, $customer_chestionar_id, $customer_chestionar_user_id, $user_id, Request $r) {


        $user = \Auth::user();
        $customer = Customer::find($customer_id);
        $customer_chestionar = CustomerChestionar::find($customer_chestionar_id);
        $customer_chestionar_user = CustomerChestionarUser::find($customer_chestionar_user_id);

        if( ! $user || ! $customer || ! $customer_chestionar || ! $customer_chestionar_user)
        {
            return redirect('/');
        }

        if($user->id != $user_id || $customer_chestionar->customer_id != $customer_id || $customer_chestionar_user->user_id != $user->id)
        {
            return redirect('/');
        }

        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/customer/chestionar-parcurgere/index.js'],
            payload: [
                'customer_id' => $customer_id,
                'customer' => $customer,
                'customer_chestionar' => $customer_chestionar,
                'customer_chestionar_user' => $customer_chestionar_user,
                'customer_user' => $user,
            ],
        );        
    }

}