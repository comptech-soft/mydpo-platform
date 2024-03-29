<?php

namespace MyDpo\Http\Controllers\Nomenclatoare\Livrabile\Centralizatoare;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Livrabile\Centralizatoare\TipCentralizator;
use MyDpo\Models\Livrabile\Centralizatoare\TipCentralizatorColoana;

class TipCentralizatorColoaneController extends Controller {
    
    public function index($centralizator_id, Request $r) {

        if(! ($centralizator = TipCentralizator::find($centralizator_id)) )
        {
            return redirect('tipuri-centralizatoare');
        }

        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/admin/tip-centralizator-coloane/index.js'],
            payload: [
                'centralizator_id' => $centralizator_id,
                'centralizator' => $centralizator,
                'columns_tree' => $centralizator->columns_tree
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