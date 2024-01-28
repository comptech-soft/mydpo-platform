<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;

class MyProfileController extends Controller {
    
    /**
     * Se ajunge pe pagina '/my-pofile'
     */
    public function index(Request $r) {

        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/user/my-profile/index.js'],
            payload: [
                'customer_user' => \Auth::user(),
            ],
        );    
    }
}