<?php

namespace MyDpo\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\System\SysRoute;

class RoutesController extends Controller {

    public function index(Request $r) {

        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/system/routes/index.js']
        );

        // return Response::View(
        //     '~templates.index', 
        //     asset('apps/system-routes/index.js'),
        //     [],
        //     $r->all()
        // );
    }

    public function getRecords(Request $r) {
        return SysRoute::getRecords($r->all());
    }

    public function doAction($action, Request $r) {
        return SysRoute::doAction($action, $r->all());
    }

}