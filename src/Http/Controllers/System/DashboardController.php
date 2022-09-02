<?php

namespace MyDpo\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;

class DashboardController extends Controller
{
    public function index(Request $r) {

        $asset = (config('app.platform') == 'admin') ? 'dashboard' : 'my-dashboard';

        return Response::View(
            '~templates.index', 
            asset('apps/' . $asset . '/index.js'),
            [],
            $r->all()
        );
    }
}