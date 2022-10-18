<?php

namespace MyDpo\Http\Middleware;

use Closure;
use MyDpo\Models\Activation;

class IsActivated {

    public function handle($request, Closure $next) {

        $user = $request->user();

        $activation = Activation::byUserAndCustomer($user->id, $request->customer_id);

        dd($activation);
        
        // if( ! $user )
        // {
        //     return redirect(config('app.url'));
        // }

        // if( config('app.platform') != 'admin')
        // {
        //     return redirect(config('app.url'));
        // }

        // if( ! $user->inRoles(['sa', 'admin', 'operator']) )
        // {
        //     return redirect(config('app.url'));
        // }
        
        return $next($request);
    }
}
