<?php

namespace MyDpo\Http\Middleware;

use Closure;

class GetLocale {

    public function handle($request, Closure $next) {

        $locale = session()->has('locale') ? session()->get('locale') : app()->getLocale();
        
        session()->put('locale', $locale);
        
        \App::setlocale($locale);
        
        return $next($request);
    }

}