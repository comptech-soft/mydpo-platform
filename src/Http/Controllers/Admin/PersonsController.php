<?php

namespace MyDpo\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;

class PersonsController extends Controller
{
    
    public function index(Request $r) {
        return Response::View(
            '~templates.index', 
            asset('apps/persons/index.js')
        );
    }

}