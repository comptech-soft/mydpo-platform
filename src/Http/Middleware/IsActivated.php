<?php

namespace MyDpo\Http\Middleware;

use Closure;

class IsActivated {

    public function handle($request, Closure $next) {

        $user = $request->user();

        dd($user->full_name, $request->customer_id);
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
