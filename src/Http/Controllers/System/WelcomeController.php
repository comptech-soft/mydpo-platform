<?php

namespace MyDpo\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WelcomeController extends Controller {
    
    public function index(Request $r) {

        return redirect(! \Auth::check() ? 'connect' : 'dashboard' );


        // if(  )
        // {
        //     return redirect(config('app.url'). '/connect');
        // }
        // return Redirect('dashboard');
    }
    
}