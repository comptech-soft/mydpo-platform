<?php

namespace MyDpo\Core\Http\Response;

use Illuminate\View\View;

class Index {

    public static function View(
        string $template = 'layout.index',
        array $styles = [],
        array $scripts = [],
        array $payload = [],
    ): View 
    {

        if( is_string($scripts) )
        {
            $scripts = [$scripts];
        }

        if( is_string($styles) )
        {
            $styles = [$styles];
        }

        $payload = [
            ...$payload,
            'session' => session()->all(),
            'old' => old(), 
            'app-name' =>  config('app.name'),
            'base-url' => config('app.url'),
            'csrf-token' => csrf_token(),
            'locale' => app()->getLocale(),
            'platform' => config('app.platform'),
            'env' => config('app.env'),
        ];

        return view($template)->withScripts($scripts)->withPayload($payload)->withStyles($styles);
    }

}