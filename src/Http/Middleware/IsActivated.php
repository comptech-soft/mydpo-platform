<?php

namespace MyDpo\Http\Middleware;

use Closure;
use MyDpo\Models\Activation;
use MyDpo\Models\CustomerAccount;
use MyDpo\Models\RoleUser;

class IsActivated {

    public function handle($request, Closure $next) {

        $user = $request->user();

        $activation = Activation::byUserAndCustomer($user->id, $request->customer_id);

        if(! $activation )
        {

            $account = CustomerAccount::where('user_id', $user->id)->where('customer_id', $request->customer_id)->first();

            if($account)
            {
                $role_user = RoleUser::where('user_id', $user->id)->where('customer_id', $request->customer_id)->first();

                if($role_user)
                {
                    $activation = Activation::createActivation($user->id, $request->customer_id, $role_user->role_id);
                }
            }            
        }

        if(true || ! $activation )
        {
            return redirect(route('account.inactive', ['customer_id' => $request->customer_id]));
        }

        if( $activation && ($activation->activated == 0) )
        {
            return redirect( route('activate.account', ['token' => $activation->token]) );
        }
        
        return $next($request);
    }
}
