<?php

namespace MyDpo\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\Chestionar;

class ChestionareController extends Controller
{
    
    public function index(Request $r) {
        return Response::View(
            '~templates.index', 
            asset('apps/chestionare/index.js')
        );
    }

    public function getItems(Request $r) {
        return Chestionar::getItems($r->all());
    }

    public function doAction($action, Request $r) {
        return Chestionar::doAction($action, $r->all());
    }

}