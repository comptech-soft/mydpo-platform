<?php

namespace MyDpo\Http\Middleware;

use Closure;

class IsB2B {

    public function handle($request, Closure $next) {

        $user = $request->user();

        if( ! $user )
        {
            return redirect(config('app.url'));
        }

        if( config('app.platform') != 'b2b ')
        {
            return redirect(config('app.url'));
        }

        if( ! $user->inRoles(['master', 'customer']) )
        {
            return redirect(config('app.url'));
        }
        
        return $next($request);
    }
}
