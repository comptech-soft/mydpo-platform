<?php

namespace MyDpo\Http\Controllers\Nomenclatoare\Livrabile\Centralizatoare;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Livrabile\Centralizatoare\TipCentralizator;

class TipuriCentralizatoareController extends Controller {
    
    public function index(Request $r) {
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/nomenclatoare/livrabile/tipuri-centralizatoare/index.js'],
        );        
    }

    public function getRecords(Request $r) {
        return TipCentralizator::getRecords($r->all());
    }

    public function doAction($action, Request $r) {
        return TipCentralizator::doAction($action, $r->all());
    }
 
}