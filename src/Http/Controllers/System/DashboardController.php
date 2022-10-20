<?php

namespace MyDpo\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;

class DashboardController extends Controller {
    
    public function index(Request $r) {

        /**
         * admin ==> dashboard
         * b2b   ==> my-dashboard
         */

        $user = \Auth::user();

        $asset = (config('app.platform') == 'admin') ? 'dashboard' : 'my-dashboard';

        if(config('app.platform') == 'admin')
        {
            if(! $user->active )
            {
                return Response::View(
                    '~templates.index', 
                    asset('apps/email-verify-prompt/index.js'),
                    [],
                    $r->all()
                );        
            }

            return Response::View(
                '~templates.index', 
                asset('apps/' . $asset . '/index.js'),
                [],
                $r->all()
            );
        }

        if($settings = $user->settings()->where('code', 'b2b-active-customer')->first())
        {
            return redirect(route('b2b.dashboard', [
                'customer_id' => $settings->value,
            ]));

        }
        
        if($user->customers->count());
        {
            return redirect(route('b2b.dashboard', [
                'customer_id' => $user->customers[0],
            ]));
        }

    
        
    }
}