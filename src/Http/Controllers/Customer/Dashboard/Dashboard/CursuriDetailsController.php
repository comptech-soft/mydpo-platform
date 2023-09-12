<?php

namespace MyDpo\Http\Controllers\Customer\Dashboard\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use MyDpo\Helpers\Response;
// use MyDpo\Models\Customer\CustomerCurs;
// use MyDpo\Models\Customer\Customer;

class CursuriDetailsController extends Controller {
    
    public function index($customer_id, $customer_curs_id, Request $r) {

        dd(__METHOD__);
        // $record = CustomerCurs::find($customer_curs_id);
        // if(! $record)
        // {
        //     $customer = Customer::find($customer_id);

        //     if(  $customer )
        //     {
        //         return redirect(config('app.url') . '/customer-cursuri/' . $customer_id);
        //     }

        //     return redirect('/');
        // }

        // return Response::View(
        //     '~templates.index', 
        //     asset('apps/customer-cursuri-acces-curs/index.js'),
        //     [], 
        //     [
        //         'customer_id' => $customer_id,
        //         'customer_curs_id' => $customer_curs_id,
        //     ]
        // );
    }

}