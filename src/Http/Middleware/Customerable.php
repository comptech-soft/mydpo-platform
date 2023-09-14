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
                return $this->Logout();
            }

            $account = Account::where('user_id', $user->id)->where('customer_id', $customer->id)->first();

            if(!! $account)
            {
                return $next($request);
            }

            dd('Nu avem cont');
        }

        dd(config('app.platform'));
        // ;

        // if( ! $customer )
        // {
        //     if(config('app.platform') == 'admin')
        //     {
        //         return redirect(config('app.url') . '/admin/clienti');
        //     }

        //     return redirect(config('app.url')); 
        // }

        // if(config('app.platform') == 'admin')
        // {
        //     return $next($request);
        // }

        // /**
        //  * Numai cei cu conturi pot intra
        //  * Sunt: Auth::user();
        //  * Accesez: url/customer_id;
        //  * Cinee trebuie sa fiu eu in raport cu customer_id?
        //  */
        //
       
        // /**
        //  * Nu avem corespondent in tabela [customers-persons]
        //  */
        // if(! $account )
        // {
        //     return redirect(config('app.url')); 
        // }

        // /**
        //  * Avem inregistrare in [customers-persons]
        //  * Ce alte restrictii mai sunt?
        //  */
        // return $next($request);
        
    }

    protected function Logout() {
        \Auth::guard('web')->logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect(config('app.url'));
    }
}
