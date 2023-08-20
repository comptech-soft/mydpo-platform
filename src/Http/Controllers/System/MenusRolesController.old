<?php

namespace MyDpo\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\System\SysMenuRole;

class MenusRolesController extends Controller {

    public function getRecords(Request $r) {
        return SysMenuRole::getRecords($r->all());
    }

    public function doAction($action, Request $r) {
        return SysMenuRole::doAction($action, $r->all());
    }

}