<?php

namespace MyDpo\Http\Middleware;

use Closure;
use MyDpo\Models\Customer\Accounts\Activation;
use MyDpo\Models\Customer\Accounts\Account;
// use MyDpo\Models\RoleUser;

class IsActivated {

    public function handle($request, Closure $next) {

        if(config('app.platform') == 'admin')
        {
            return $next($request);
        }

        if(! $request->customer_id)
        {
            return redirect( config('app.url') . '/my-customers');
        }
        
        $account = Account::where('user_id', $user->id)->where('customer_id', $request->customer_id)->first();


        dd($account);
        
        $user = \Auth::user();

        dd($user->role);


        if( ! $user->inRoles(['master', 'customer']) )
        {
            return redirect( config('app.url') . '/my-customers');
        }
        
        $activation = Activation::byUserAndCustomer($user->id, $request->customer_id, $user->role->id);

        
        
        dd( $activation);
        
        if(! $activation )
        {

            

            if($account)
            {
                $role_user = RoleUser::where('user_id', $user->id)->where('customer_id', $request->customer_id)->first();

                if($role_user)
                {
                    $activation = Activation::createActivation($user->id, $request->customer_id, $role_user->role_id);
                }
            }            
        }

        if(! $activation )
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
