<?php

namespace MyDpo\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\System\SysRole;

class RolesController extends Controller {


    public function getRecords(Request $r) {
        return SysRole::getRecords($r->all());
    }


}