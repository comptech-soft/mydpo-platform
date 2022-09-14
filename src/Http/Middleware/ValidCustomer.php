<?php

namespace MyDpo\Http\Middleware;

use Closure;
use MyDpo\Models\Customer;
use MyDpo\Models\CustomerAccount;

class ValidCustomer {

    public function handle($request, Closure $next) {
        
        $customer = Customer::find($request->customer_id);

        if( ! $customer )
        {
            if(config('app.platform') == 'admin')
            {
                return redirect(config('app.url') . '/admin/clienti');
            }

            return redirect(config('app.url')); 
        }

        if(config('app.platform') == 'admin')
        {
            return $next($request);
        }

        /**
         * Numai cei cu conturi pot intra
         */
        $customers = CustomerAccount::where('user_id', \Auth::user()->id)->with(['customer'])->get(['customers-persons.id', 'customer.name'])->select()->toArray();
        dd($customer->id, \Auth::user()->id, $customers);
        
    }
}
