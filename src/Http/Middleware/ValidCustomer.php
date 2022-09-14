<?php

namespace MyDpo\Http\Middleware;

use Closure;
use MyDpo\Models\Customer;


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

        dd($customer->id, \Auth::user()->id);
        
    }
}
