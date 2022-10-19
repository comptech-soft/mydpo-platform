<?php

namespace MyDpo\Http\Middleware;

use Closure;
use MyDpo\Models\Activation;
use MyDpo\Models\CustomerAccount;

class IsActivated {

    public function handle($request, Closure $next) {

        $user = $request->user();

        $activation = Activation::byUserAndCustomer($user->id, $request->customer_id);

        if(! $activation )
        {

            $account = CustomerAccount::where('user_id', $user_id)->where('customer_id', $request->customer_id)->first();

            dd($account);

            return redirect(route('account.inactive', ['customer_id' => $request->customer_id]));
        }

        if( $activation && ($activation->activated == 0) )
        {
            return redirect( route('activate.account', ['token' => $activation->token]) );
        }
        
        return $next($request);
    }
}
