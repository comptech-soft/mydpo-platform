<?php

namespace MyDpo\Http\Middleware;

use Closure;
use MyDpo\Models\Customer\Customer;
use MyDpo\Models\Customer\Customer\CustomerAccount;

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
         * Sunt: Auth::user();
         * Accesez: url/customer_id;
         * Cinee trebuie sa fiu eu in raport cu customer_id?
         */
        $account = CustomerAccount::where('user_id', \Auth::user()->id)->where('customer_id', $customer->id)->first();
       
        /**
         * Nu avem corespondent in tabela [customers-persons]
         */
        if(! $account )
        {
            return redirect(config('app.url')); 
        }

        /**
         * Avem inregistrare in [customers-persons]
         * Ce alte restrictii mai sunt?
         */
        return $next($request);
        
    }
}
