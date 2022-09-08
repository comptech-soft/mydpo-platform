<?php

namespace MyDpo\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\CursType;

class CursuritypesController extends Controller {

    public function getItems($type = NULL, Request $r) {
        return CursType::getItems($r->all(), $type);
    }

}