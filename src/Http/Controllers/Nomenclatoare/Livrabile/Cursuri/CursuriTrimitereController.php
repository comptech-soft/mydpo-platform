<?php

namespace MyDpo\Http\Controllers\Nomenclatoare\Livrabile\Cursuri;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Nomenclatoare\Livrabile\ELearning\Sharematerial;

class CursuriTrimitereController extends Controller {
    
    public function index(Request $r) {

        Sharematerial::SyncRecords();

        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/nomenclatoare/livrabile/cursuri/trimiteri/index.js'],
            payload: [
                'type' => 'curs',
            ],
        );
    }

    public function getRecords(Request $r) {
        return Sharematerial::getRecords($r->all());
    }

}