<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;

class MyCustomersController extends Controller {
    
    public function index(Request $r) {

        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/user/my-customers/index.js']
        ); 

    }

}