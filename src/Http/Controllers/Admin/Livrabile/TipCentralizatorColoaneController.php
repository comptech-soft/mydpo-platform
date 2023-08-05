<?php

namespace MyDpo\Http\Controllers\Admin\Livrabile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Livrabile\TipCentralizator;
use MyDpo\Models\Livrabile\TipCentralizatorColoana;

class TipCentralizatorColoaneController extends Controller {
    
    public function index($centralizator_id, Request $r) {

        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/admin/tip-centralizator-coloane/index.js'],
            payload: [
                'centralizator_id' => $centralizator_id,
                'centralizator' => TipCentralizator::find($centralizator_id),
            ],
        );        
    }

    public function getRecords(Request $r) {
        return TipCentralizatorColoana::getRecords($r->all());
    }

    public function doAction($action, Request $r) {
        return TipCentralizatorColoana::doAction($action, $r->all());
    }

    
    
}