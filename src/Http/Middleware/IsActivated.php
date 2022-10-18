<?php

namespace MyDpo\Http\Middleware;

use Closure;

class IsActivated {

    public function handle($request, Closure $next) {

        dd(__METHOD__, __FILE__);
        // $user = $request->user();

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
