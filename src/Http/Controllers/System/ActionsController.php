<?php

namespace MyDpo\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\System\SysAction;

class ActionsController extends Controller {

    public function index(Request $r) 
    {
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/system/actions/index.js']
        );
    }

    // public function index(Request $r) {

    //     dd();
    //     return Response::View(
    //         '~templates.index', 
    //         asset('apps/system-actions/index.js'),
    //         [],
    //         $r->all()
    //     );
    // }

    public function getRecords(Request $r) {
        return SysAction::getRecords($r->all());
    }

    public function doAction($action, Request $r) {
        return SysAction::doAction($action, $r->all());
    }

    // public function getVisibilities(Request $r) {
    //     return SysMenu::getVisibilities($r->all());
    // }

    // public function reorder(Request $r) {
    //     return SysMenu::reorder($r->all());
    // }


}