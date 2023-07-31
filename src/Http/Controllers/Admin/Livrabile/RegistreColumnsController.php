<?php

namespace MyDpo\Http\Controllers\Admin\Livrabile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\Livrabile\RegistruColoana;

class RegistreColumnsController extends Controller {
    
    public function getRecords(Request $r) {
        return RegistruColoana::getRecords($r->all());
    }

    // public function doAction($action, Request $r) {
    //     return RegistruColoana::doAction($action, $r->all());
    // }

    // public function reorderColumns(Request $r) {
    //     return RegistruColoana::reorderColumns($r->all());
    // }

}