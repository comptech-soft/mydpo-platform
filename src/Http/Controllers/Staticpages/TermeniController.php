<?php

namespace MyDpo\Http\Controllers\Staticpages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;

class TermeniController extends Controller {

    public function index(Request $r) {
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/footer/termeni/index.js']
        );
    }

}