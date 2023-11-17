<?php

namespace MyDpo\Http\Controllers\Nomenclatoare\Livrabile\Registre;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Livrabile\Registre\TipRegistru;
use MyDpo\Models\Livrabile\Registre\TipRegistruColoana;

class TipRegistruColoaneController extends Controller {
    
    public function index($tip_registru_id, Request $r) {

        if(! ($tip_registru = TipRegistru::find($tip_registru_id)) )
        {
            return redirect('tipuri-registre');
        }

        dd($tip_registru->columns->toArray());

        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/admin/tip-registru-coloane/index.js'],
            payload: [
                'tip_registru_id' => $tip_registru_id,
                'tip_registru' => $tip_registru,
            ],
        );        
    }

    public function getRecords(Request $r) {
        return TipRegistruColoana::getRecords($r->all());
    }

    public function doAction($action, Request $r) {
        return TipRegistruColoana::doAction($action, $r->all());
    }

}