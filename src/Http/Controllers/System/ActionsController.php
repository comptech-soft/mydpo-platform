<?php

namespace MyDpo\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
// use MyDpo\Models\System\SysMenu;

class ActionsController extends Controller {

    public function index(Request $r) {
        return Response::View(
            '~templates.index', 
            asset('apps/system-actions/index.js'),
            [],
            $r->all()
        );
    }

    // public function getRecords(Request $r) {
    //     return SysMenu::getRecords($r->all());
    // }

    // public function doAction($action, Request $r) {
    //     return SysMenu::doAction($action, $r->all());
    // }

    // public function getVisibilities(Request $r) {
    //     return SysMenu::getVisibilities($r->all());
    // }

    // public function reorder(Request $r) {
    //     return SysMenu::reorder($r->all());
    // }


}