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

            // $this->loadTranslationsFrom(__DIR__ . '/../Lang', 'mydpo-platform');

            // $this->publishes([
                
            //     __DIR__ . '/../Lang/ro' => lang_path('ro')

            // ], 'mydpo-platform');
            
        //     $this->loadRoutesFrom(__DIR__ . '/../Routes/web/system/get-config.php');
        //     $this->loadRoutesFrom(__DIR__ . '/../Routes/web/system/set-locale.php');
        //     $this->loadRoutesFrom(__DIR__ . '/../Routes/web/system/user-session.php');

        //     $this->loadRoutesFrom(__DIR__ . '/../Routes/web/system/configs.php');
        //     $this->loadRoutesFrom(__DIR__ . '/../Routes/web/system/countries.php');
        //     $this->loadRoutesFrom(__DIR__ . '/../Routes/web/system/database.php');
        //     $this->loadRoutesFrom(__DIR__ . '/../Routes/web/system/validation.php');

        //     /**  Cartalyst Routes */
        //     $this->loadRoutesFrom(__DIR__ . '/../Routes/web/cartalyst/permissions.php');
        //     $this->loadRoutesFrom(__DIR__ . '/../Routes/web/cartalyst/roles.php');
        //     $this->loadRoutesFrom(__DIR__ . '/../Routes/web/cartalyst/users.php');

        //     $this->loadRoutesFrom(__DIR__ . '/../Routes/web/decalex-admin/static-pages.php');

        //     /** Decalex commons routes */

        //     $this->loadRoutesFrom(__DIR__ . '/../Routes/web/decalex-admin/registre.php');

        //     $this->loadRoutesFrom(__DIR__ . '/../Routes/web/decalex-admin/customers-departamente.php');
        //     $this->loadRoutesFrom(__DIR__ . '/../Routes/web/decalex-admin/customers-persons.php');
        //     $this->loadRoutesFrom(__DIR__ . '/../Routes/web/decalex-admin/customers-monthly-reports.php');
        //     $this->loadRoutesFrom(__DIR__ . '/../Routes/web/decalex-admin/customers-contracts.php');
        //     $this->loadRoutesFrom(__DIR__ . '/../Routes/web/decalex-admin/customers-orders.php');
        //     $this->loadRoutesFrom(__DIR__ . '/../Routes/web/decalex-admin/customers-folders.php');
        //     $this->loadRoutesFrom(__DIR__ . '/../Routes/web/decalex-admin/customers-centralizatoare.php');
        //     $this->loadRoutesFrom(__DIR__ . '/../Routes/web/decalex-admin/customers-chestionare.php');
        //     $this->loadRoutesFrom(__DIR__ . '/../Routes/web/decalex-admin/customers-cursuri.php');
        //     $this->loadRoutesFrom(__DIR__ . '/../Routes/web/decalex-admin/customers-registers.php');

            
            
        //     if(config('app.platform') == 'admin')
        //     {
        //         /**  Decalex admin Routes */
        //         $this->loadRoutesFrom(__DIR__ . '/../Routes/web/decalex-admin/categorii.php');
        //         $this->loadRoutesFrom(__DIR__ . '/../Routes/web/decalex-admin/centralizatoare.php');
        //         $this->loadRoutesFrom(__DIR__ . '/../Routes/web/decalex-admin/chestionare.php');
                
        //         $this->loadRoutesFrom(__DIR__ . '/../Routes/web/decalex-admin/customers-notifications.php');
                                
        //         $this->loadRoutesFrom(__DIR__ . '/../Routes/web/decalex-admin/customers-services.php');
        //         $this->loadRoutesFrom(__DIR__ . '/../Routes/web/decalex-admin/customers.php');
        //         $this->loadRoutesFrom(__DIR__ . '/../Routes/web/decalex-admin/educatie.php');
        //         $this->loadRoutesFrom(__DIR__ . '/../Routes/web/decalex-admin/educatie.php');
        //         $this->loadRoutesFrom(__DIR__ . '/../Routes/web/decalex-admin/email-templates.php');
        //         $this->loadRoutesFrom(__DIR__ . '/../Routes/web/decalex-admin/notifications.php');
        //         $this->loadRoutesFrom(__DIR__ . '/../Routes/web/decalex-admin/planning.php');
        //         $this->loadRoutesFrom(__DIR__ . '/../Routes/web/decalex-admin/rapoarte.php');
               
        //         $this->loadRoutesFrom(__DIR__ . '/../Routes/web/decalex-admin/services.php');
                
        //         $this->loadRoutesFrom(__DIR__ . '/../Routes/web/decalex-admin/tasks.php');
        //         $this->loadRoutesFrom(__DIR__ . '/../Routes/web/decalex-admin/team-customers.php');
        //         $this->loadRoutesFrom(__DIR__ . '/../Routes/web/decalex-admin/team.php');

        //         $this->loadRoutesFrom(__DIR__ . '/../Routes/web/decalex-admin/trimiteri.php');
        //         $this->loadRoutesFrom(__DIR__ . '/../Routes/web/decalex-admin/share.php');

        //     }

        //     if(config('app.platform') == 'b2b')
        //     {
        //         // $this->loadRoutesFrom(__DIR__ . '/../Routes/web/decalex-b2b/centralizatoare.php');
        //         // $this->loadRoutesFrom(__DIR__ . '/../Routes/web/decalex-b2b/chestionare.php');
        //         // $this->loadRoutesFrom(__DIR__ . '/../Routes/web/decalex-b2b/contracts.php');
        //         // $this->loadRoutesFrom(__DIR__ . '/../Routes/web/decalex-b2b/cursuri.php');
        //         $this->loadRoutesFrom(__DIR__ . '/../Routes/web/decalex-b2b/customer-profile.php');
        //         $this->loadRoutesFrom(__DIR__ . '/../Routes/web/decalex-b2b/customer-team.php');
        //         $this->loadRoutesFrom(__DIR__ . '/../Routes/web/decalex-b2b/customers.php');
        //         // $this->loadRoutesFrom(__DIR__ . '/../Routes/web/decalex-b2b/documente.php');
        //         $this->loadRoutesFrom(__DIR__ . '/../Routes/web/decalex-b2b/dpia.php');
        //         $this->loadRoutesFrom(__DIR__ . '/../Routes/web/decalex-b2b/notifications.php');
        //         //$this->loadRoutesFrom(__DIR__ . '/../Routes/web/decalex-b2b/persons.php');
        //         $this->loadRoutesFrom(__DIR__ . '/../Routes/web/decalex-b2b/plan-conformare.php');
        //         // $this->loadRoutesFrom(__DIR__ . '/../Routes/web/decalex-b2b/registre.php');
        //         $this->loadRoutesFrom(__DIR__ . '/../Routes/web/decalex-b2b/team.php');
        //         $this->loadRoutesFrom(__DIR__ . '/../Routes/web/decalex-b2b/users-settings.php');
        //     }

        });

        // $this->loadViewsFrom(__DIR__ . '/../Views', 'decalex-b2b-commons');
    }

    private function RegisterSysRoutes() {

        $routes = SysRoute::whereIsRoot()->whereType('Route')->get();

        foreach($routes as $i => $route)
        {
            $route_registrar = Route::prefix($route['prefix']);

            if($route['middleware'])
            {
                $route_registrar->middleware($route['middleware']);
            }

            $route_registrar->{$route['verb']}(
                $route['path'],
                [
                    $route['controller'],
                    $route['method'],
                ]
            );

        }
    }
}