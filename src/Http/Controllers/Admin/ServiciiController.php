<?php

namespace MyDpo\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\Service;

class ServiciiController extends Controller {
    
    public function index(Request $r) {
        return Response::View(
            '~templates.index', 
            asset('apps/servicii/index.js')
        );
    }

    public function getItems(Request $r) {
        return Service::getItems($r->all());
    }
}