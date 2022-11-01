<?php

namespace MyDpo\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;

class B2bDashboardController extends Controller {
    
    public function index($customer_id, Request $r) {

        dd('EXPIRED.....');

        /**
         * admin ==> dashboard
         * b2b   ==> my-dashboard
         */

        $user = \Auth::user();

        $asset = (config('app.platform') == 'admin') ? 'dashboard' : 'my-dashboard';

        return Response::View(
            '~templates.index', 
            asset('apps/' . $asset . '/index.js'),
            [],
            [
                ...$r->all(),
                'customer_id' => $customer_id,
            ],
        );

    }
}