<?php

namespace MyDpo\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;

class TeamController extends Controller {
    
    public function index(Request $r) {

       dd(__METHOD__);

        return Response::View(
            '~templates.index', 
            asset('apps/team/index.js')
        );
    }

}