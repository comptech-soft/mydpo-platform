<?php

namespace MyDpo\Http\Middleware;

use Closure;

use MyDpo\Models\Customer\Customer;
use MyDpo\Models\Customer\Accounts\Account;

class Customerable {
    
    public function handle($request, Closure $next) {
        
        $user = \Auth::user();
        
        if(config('app.platform') == 'b2b')
        {
            /**
             * Suntem pe platforma client
             */
            if(! ($customer = Customer::find($request->customer_id)) )
            {
                /**
                 * Nici macar nu avem clientul in tabela de clienti
                 */
                return redirect(config('app.url') . '/my-customers'); 
            }
            
            $account = Account::where('user_id', $user->id)->where('customer_id', $customer->id)->first();

            if(!! $account)
            {
                return $next($request);
            }

            /**
             * Userul logat nu are cont la clientul specificat
             */
            return redirect(config('app.url') . '/my-customers'); 
        }

        /**
         * Suntem pe platforma admin
         */
        if(! ($customer = Customer::find($request->customer_id)) )
        {
            /**
             * Redirectam catre lista de clienti
             */
            return redirect(config('app.url') . '/clienti'); 
        }
      
        return $next($request);
    }

    protected function Logout() {
        \Auth::guard('web')->logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect(config('app.url'));
    }
}
