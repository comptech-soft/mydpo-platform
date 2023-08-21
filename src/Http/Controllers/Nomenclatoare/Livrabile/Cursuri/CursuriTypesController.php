<?php

namespace MyDpo\Http\Controllers\Nomenclatoare\Livrabile\Cursuri;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use MyDpo\Helpers\Response;
use MyDpo\Models\Nomenclatoare\Livrabile\ELearning\Type;

class CursuriTypesController extends Controller {

    public function getRecords(Request $r) {
        return Type::getRecords($r->all());
    }

}