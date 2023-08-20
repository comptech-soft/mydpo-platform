<?php

namespace MyDpo\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
// use MyDpo\Helpers\Response;
// use MyDpo\Models\System\SysConfig;

class ConfigController extends Controller {
    
    public function index(Request $r) {

        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/system/config/index.js']
        );
        // return Response::View(
        //     '~templates.index', 
        //     asset('apps/config/index.js')
        // );
    }

    // public function getItems(Request $r) {
    //     return SysConfig::getItems($r->all());
    // }

    // public function doAction($action, Request $r) {
    //     return SysConfig::doAction($action, $r->all());
    // }
}