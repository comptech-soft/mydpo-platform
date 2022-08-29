<?php

namespace MyDpo\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\Curs;

class CursuriController extends Controller
{
    
    public function index(Request $r) {
        return Response::View(
            '~templates.index', 
            asset('apps/cursuri/index.js')
        );
    }

    public function getItems(Request $r) {
        return Curs::getItems($r->all());
    }

    public function doAction($action, Request $r) {
        return Curs::doAction($action, $r->all());
    }

}