<?php

namespace MyDpo\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;

class ContactController extends Controller
{

    public function index(Request $r) 
    {
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/footer/contact/index.js']
        );
    }

}