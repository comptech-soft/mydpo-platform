<?php

namespace MyDpo\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;

class ContactController extends Controller
{

    public function index(Request $r) 
    {
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/system/contact/index.js']
        );
    }

    // public function index(Request $r) {
    //     return Response::View(
    //         '~templates.index', 
    //         asset('apps/contact/index.js'),
    //         [],
    //         $r->all()
    //     );
    // }

}