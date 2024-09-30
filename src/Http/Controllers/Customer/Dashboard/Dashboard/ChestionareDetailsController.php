<?php

namespace MyDpo\Http\Controllers\Customer\Dashboard\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer\Chestionare\CustomerChestionar;
use MyDpo\Models\Customer\Customer;

class ChestionareDetailsController extends Controller {
    
    public function index($customer_id, $customer_chestionar_id, Request $r) {

        if(! ($customer = Customer::find($customer_id)) )
        {
            return redirect('clienti');
        }

        if(! ($customer_chestionar = CustomerChestionar::find($customer_chestionar_id)) )
        {
            return redirect(config('app.url') . '/customer-chestionare/' . $customer_id);
        }

        dd(__METHOD__);

        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/customer/chestionare-details/index.js'],
            payload: [
                'customer_id' => $customer_id,
                'customer' => $customer,
                'customer_chestionar_id' => $customer_chestionar_id,
                'customer_chestionar' => $customer_chestionar,
                'customer_user' => \Auth::user(),
            ],
        );     

    }

}