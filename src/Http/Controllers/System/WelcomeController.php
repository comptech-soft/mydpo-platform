<?php

namespace MyDpo\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index(Request $r) {
        if( ! \Auth::check() )
        {
            return redirect(config('app.url'). '/login');
        }
        return Redirect('dashboard');
    }
}