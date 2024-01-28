<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MyProfileController extends Controller {
    
    /**
     * Se ajunge pe pagina '/my-pofile'
     */
    public function index(Request $r) {

        dd(__METHOD__);

        // return Response::View(
        //     '~templates.index', 
        //     asset('apps/login/index.js')
        // );
    }
}