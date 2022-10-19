<?php

namespace MyDpo\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;

class AccountInactiveController extends Controller {
    
    public function index($customer_id, Request $r) {
        return Response::View(
            '~templates.index', 
            asset('apps/cont-inactiv/index.js'),
            [],
            $r->all()
        );
    }
}