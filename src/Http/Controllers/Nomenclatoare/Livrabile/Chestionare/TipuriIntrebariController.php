<?php

namespace MyDpo\Http\Controllers\Nomenclatoare\Livrabile\Chestionare;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
// use MyDpo\Models\Livrabile\Centralizatoare\TipCentralizator;

class TipuriIntrebariController extends Controller {
    
    public function index(Request $r) {
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/nomenclatoare/livrabile/tipuri-intrebari/index.js'],
        );        
    }

    public function getRecords(Request $r) {
        return TipCentralizator::getRecords($r->all());
    }

    public function doAction($action, Request $r) {
        return TipCentralizator::doAction($action, $r->all());
    }
 
}