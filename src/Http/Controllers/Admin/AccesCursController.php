<?php

namespace MyDpo\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\Curs;

class AccesCursController extends Controller {
    
    public function index($curs_id, Request $r) {

        return Response::View(
            '~templates.index', 
            asset('apps/acces-curs/index.js'),
            [], 
            [
                'curs_id' => $curs_id
            ]
        );
    }

    

}