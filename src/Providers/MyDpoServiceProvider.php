<?php

namespace MyDpo\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use MyDpo\Models\System\SysRoute;

class MyDpoServiceProvider extends ServiceProvider {

    public function boot() {

        Route::middleware('web')->group(function () {

            $this->loadRoutesFrom(__DIR__ . '/../Routes/routes.php');

            $this->RegisterSysRoutes();
        
        });

    }

    private function RegisterSysRoutes() {

        $routes = SysRoute::whereIsRoot()->whereType('Route')->orderBy('order_no')->get();

        foreach($routes as $i => $route)
        {
            if(!! $route->platform && in_array(config('app.platform'), $route->platform) )
            {
                $route_registrar = Route::prefix($route->prefix);

                if($route->middleware)
                {
                    $route_registrar->middleware($route->middleware);
                }

                $route_registrar->{$route->verb}(
                    $route->path,
                    [
                        'MyDpo\\Http\Controllers\\' . $route->controller->namespace . '\\' . $route->controller->controller,
                        $route->method,
                    ]
                );
            }
        }
    }
}