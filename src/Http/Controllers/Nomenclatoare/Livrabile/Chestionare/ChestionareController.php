<?php

namespace MyDpo\Http\Controllers\Nomenclatoare\Livrabile\Chestionare;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Livrabile\Chestionare\Chestionar;

class ChestionareController extends Controller {
    
    public function index(Request $r) {
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/nomenclatoare/livrabile/chestionare/index.js'],            
        );
    }

    public function getRecords(Request $r) {
        return Chestionar::getRecords($r->all());
    }

    public function doAction($action, Request $r) {
        return Chestionar::doAction($action, $r->all());
    }
 
}