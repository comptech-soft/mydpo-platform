<?php

namespace MyDpo\Http\Middleware;

use Closure;
use MyDpo\Models\Customer\Accounts\Activation;
use MyDpo\Models\Customer\Accounts\Account;
use MyDpo\Models\Authentication\RoleUser;

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
        
        $user = \Auth::user();

        $account = Account::where('user_id', $user->id)->where('customer_id', $request->customer_id)->first();

        if(! $account )
        {
            return redirect( config('app.url') . '/my-customers');
        }
        
        $activation = Activation::byUserAndCustomer($account->user_id, $account->customer_id, $account->role_id);
        $role_user = RoleUser::byUserAndCustomer($account->user_id, $account->customer_id, $account->role_id);
        
        if( ! $user->inRoles(['master', 'customer']) )
        {
            return redirect( config('app.url') . '/my-customers');
        }

        if($activation->activated == 0)
        {
            return redirect( route('activate.account', ['token' => $activation->token]) );
        }
        
        return $next($request);
    }
}