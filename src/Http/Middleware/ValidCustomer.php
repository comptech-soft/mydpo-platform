<?php

namespace MyDpo\Http\Middleware;

use Closure;
use MyDpo\Models\Customer;


class ValidCustomer {

    public function handle($request, Closure $next) {

        if(config('app.platform') == 'b2b')
        {
            return redirect(config('app.url')); 
        }

        $customer = Customer::find($request->customer_id);

        if( ! $customer )
        {
            return redirect(config('app.url') . '/admin/clienti');
        }

        return $next($request);
    }
}
