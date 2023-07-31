<?php

namespace MyDpo\Http\Controllers\Admin\Livrabile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\Livrabile\CentralizatorColoana;

class CentralizatoareColumnsController extends Controller {
    
    // public function getItems(Request $r) {
    //     return CentralizatorColoana::getItems($r->all());
    // }

    public function getRecords(Request $r) {
        return CentralizatorColoana::getRecords($r->all());
    }

    public function doAction($action, Request $r) {
        return CentralizatorColoana::doAction($action, $r->all());
    }
    
}