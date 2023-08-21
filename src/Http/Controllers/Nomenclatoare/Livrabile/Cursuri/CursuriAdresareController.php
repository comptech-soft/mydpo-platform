<?php

namespace MyDpo\Http\Controllers\Nomenclatoare\Livrabile\Cursuri;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\Nomenclatoare\Livrabile\ELearning\Adresare;

class CursuriAdresareController extends Controller {

    public function getRecords(Request $r) {
        return Adresare::getRecords($r->all());
    }

    public function doAction($action, Request $r) {
        return Adresare::doAction($action, $r->all());
    }

}