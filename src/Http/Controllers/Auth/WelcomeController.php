<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WelcomeController extends Controller {
    
    /**
     * Se ajunge pe pagina '/'
     * Se redirecteaza catre dashboard sau connect un functie de existenta/inexistenta utilizatorului conectat
     */
    public function index(Request $r) {
        return redirect(! \Auth::check() ? 'connect' : 'dashboard' );
    }

}