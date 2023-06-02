<?php

namespace MyDpo\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;

class MenusController extends Controller {

    public function index(Request $r) {
        return Response::View(
            '~templates.index', 
            asset('apps/system-menus/index.js'),
            [],
            $r->all()
        );
    }

}