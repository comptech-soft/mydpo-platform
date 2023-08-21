<?php

namespace MyDpo\Http\Controllers\Nomenclatoare\Livrabile\Cursuri;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\Nomenclatoare\Livrabile\ELearning\Adresare;

class CursuriTypesController extends Controller {

    public function getRecords(Request $r) {
        return Adresare::getRecords($r->all());
    }

}