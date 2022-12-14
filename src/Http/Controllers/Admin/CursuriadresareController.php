<?php

namespace MyDpo\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\Cursadresare;

class CursuriadresareController extends Controller {

    public function getItems(Request $r) {
        return Cursadresare::getItems($r->all());
    }

    public function doAction($action, Request $r) {
        return Cursadresare::doAction($action, $r->all());
    }

}