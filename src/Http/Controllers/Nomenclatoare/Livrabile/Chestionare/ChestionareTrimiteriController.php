<?php

namespace MyDpo\Http\Controllers\Nomenclatoare\Livrabile\Chestionare;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Nomenclatoare\Livrabile\ELearning\Sharematerial;

class ChestionareTrimiteriController extends Controller {
    
    public function index(Request $r) {

        Sharematerial::SyncRecords();

        return Index::View(
            styles: ['css/app.css'],
            // scripts: ['apps/nomenclatoare/livrabile/cursuri/trimiteri/index.js'],
            scripts: ['apps/nomenclatoare/livrabile/chestionare/trimiteri/index.js'],
            payload: [
                'type' => 'chestionar',
            ],
        );
    }

    public function getRecords(Request $r) {
        return Sharematerial::getRecords($r->all());
    }

    public function doAction($action, Request $r) {
        return Sharematerial::doAction($action, $r->all());
    }

}