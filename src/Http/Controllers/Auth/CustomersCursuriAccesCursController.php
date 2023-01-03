<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\CustomerCurs;
use MyDpo\Models\Customer;

class CustomersCursuriAccesCursController extends Controller {
    
    public function index($customer_id, $customer_curs_id, Request $r) {

        $record = CustomerCurs::find($customer_curs_id);
        if(! $record)
        {
            $customer = Customer::find($customer_id);

            if(  $customer )
            {
                return redirect(config('app.url') . '/customer-cursuri/' . $customer_id);
            }

            return redirect('/');
        }

        return Response::View(
            '~templates.index', 
            asset('apps/customer-cursuri-acces-curs/index.js'),
            [], 
            [
                'customer_id' => $customer_id,
                'customer_curs_id' => $customer_curs_id,
            ]
        );
    }

}