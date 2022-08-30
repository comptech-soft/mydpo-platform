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

        if( ! $user->inRoles(['sa']) )
        {
            return redirect(config('app.url'));
        }
        
        return $next($request);
    }
}
