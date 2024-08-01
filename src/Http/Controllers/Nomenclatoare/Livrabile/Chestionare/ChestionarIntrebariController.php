<?php

namespace MyDpo\Http\Controllers\Nomenclatoare\Livrabile\Chestionare;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Livrabile\Chestionare\ChestionarQuestion;

class ChestionarIntrebariController extends Controller {
    
    public function index($id, Request $r) {

        $chestionar = Chestionar::find($id);

        if(! $chestionar )
        {
            return redirect('chestionare');
        }

        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/nomenclatoare/livrabile/chestionar-intrebari/index.js'],
            payload: [
                'chestionar' => $chestionar,
            ],
        );        
    }

    public function getRecords(Request $r) {
        return ChestionarQuestion::getRecords($r->all());
    }

    public function doAction($action, Request $r) {
        return ChestionarQuestion::doAction($action, $r->all());
    }
}