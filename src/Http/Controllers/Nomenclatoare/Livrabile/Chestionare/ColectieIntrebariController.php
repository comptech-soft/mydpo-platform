<?php

namespace MyDpo\Http\Controllers\Nomenclatoare\Livrabile\Chestionare;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
// use MyDpo\Models\Nomenclatoare\Livrabile\Chestionare\TipIntrebare;

class ColectieIntrebariController extends Controller {
    
    public function index(Request $r) {

        dd(__FILE__);
        
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/nomenclatoare/livrabile/tipuri-intrebari/index.js'],
        );        
    }

    public function getRecords(Request $r) {
        return TipIntrebare::getRecords($r->all());
    }

    public function doAction($action, Request $r) {
        return TipIntrebare::doAction($action, $r->all());
    }
 
}