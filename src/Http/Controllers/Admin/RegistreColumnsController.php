<?php

namespace MyDpo\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\RegistruColoana;

class RegistreColumnsController extends Controller {
    
    public function doAction($action, Request $r) {
        return RegistruColoana::doAction($action, $r->all());
    }

    public function reorderColumns(Request $r) {
        return RegistruColoana::reorderColumns($r->all());
    }

}