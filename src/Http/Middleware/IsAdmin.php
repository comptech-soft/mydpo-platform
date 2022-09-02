<?php

namespace MyDpo\Http\Middleware;

use Closure;

class IsAdmin {

    public function handle($request, Closure $next) {

        $user = $request->user();

        if( ! $user )
        {
            return redirect(config('app.url'));
        }

        if( config('app.platform') != 'admin')
        {
            return redirect(config('app.url'));
        }

        if( ! $user->inRoles(['sa', 'admin', 'operator']) )
        {
            return redirect(config('app.url'));
        }
        
        return $next($request);
    }
}
