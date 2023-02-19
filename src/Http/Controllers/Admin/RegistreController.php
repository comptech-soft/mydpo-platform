<?php

namespace MyDpo\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\Registru;

class RegistreController extends Controller {
    
    public function index(Request $r) {
        return Response::View(
            '~templates.index', 
            asset('apps/registre/index.js')
        );
    }

    public function getItems(Request $r) {
        return Registru::getItems($r->all());
    }

    public function doAction($action, Request $r) {
        return Registru::doAction($action, $r->all());
    }

}