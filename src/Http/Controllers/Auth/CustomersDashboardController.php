<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\Customer;

class CustomersDashboardController extends Controller {
    
    public function index($customer_id,Request $r) {

        $customer = Customer::find($customer_id);

        if( ! $customer )
        {
            if(config('app.platform') == 'admin')
            {
                return redirect(config('app.url') . '/admin/clienti');
            }
            return redirect(config('app.url')); 
        }

        return Response::View(
            '~templates.index', 
            asset('apps/customer-dashboard/index.js'),
            [], 
            [
                'customer_id' => $customer_id,
            ]
        );
    }


}