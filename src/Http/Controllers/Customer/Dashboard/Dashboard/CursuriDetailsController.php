<?php

namespace MyDpo\Http\Controllers\Customer\Dashboard\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer\ELearning\CustomerCurs;
use MyDpo\Models\Customer\Customer;

class CursuriDetailsController extends Controller {
    
    public function index($customer_id, $customer_curs_id, Request $r) {

        if(! ($customer = Customer::find($customer_id)) )
        {
            return redirect('clienti');
        }

        if(! ($customer_curs = CustomerCurs::find($customer_curs_id)) )
        {
            return redirect(config('app.url') . '/customer-elearning/' . $customer_id);
        }

        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/customer/elearning-details/index.js'],
            payload: [
                'customer_id' => $customer_id,
                'customer' => Customer::find($customer_id),
                'customer_curs_id' => $customer_curs_id,
                'customer_curs' => $customer_curs,
                'customer_user' => \Auth::user(),
            ],
        );     

    }

}