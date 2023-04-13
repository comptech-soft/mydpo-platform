<?php

namespace MyDpo\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;

class UsersController extends Controller {
    
    public function index(Request $r) {
        return Response::View(
            '~templates.index', 
            asset('apps/utilizatori-platforma/index.js')
        );
    }

};