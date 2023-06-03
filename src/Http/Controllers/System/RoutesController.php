<?php

namespace MyDpo\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\System\SysRoute;

class RoutesController extends Controller {

    public function index(Request $r) {
        return Response::View(
            '~templates.index', 
            asset('apps/system-routes/index.js'),
            [],
            $r->all()
        );
    }

    public function getRecords(Request $r) {
        return SysRoute::getRecords($r->all());
    }

}