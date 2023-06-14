<?php

namespace MyDpo\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use MyDpo\Helpers\Response;
use MyDpo\Models\System\SysMenu;

class MenusController extends Controller {


    /**
     * Afisarea paginii system/menus
     * Definirea meniurilor
     * Tabela: system-menus
     */
    public function index(Request $r) 
    {
        
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/system/menus/index.js']
        );

        // return Response::View(
        //     '~templates.index', 
        //     asset('apps/system-menus/index.js'),
        //     [],
        //     $r->all()
        // );
    }

    public function getRecords(Request $r) {
        return SysMenu::getRecords($r->all());
    }

    public function doAction($action, Request $r) {
        return SysMenu::doAction($action, $r->all());
    }

    public function getVisibilities(Request $r) {
        return SysMenu::getVisibilities($r->all());
    }

    public function reorder(Request $r) {
        return SysMenu::reorder($r->all());
    }


}