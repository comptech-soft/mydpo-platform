<?php

namespace MyDpo\Http\Controllers\Usersession;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;

class MyProfileController extends Controller
{
    
    public function index(Request $r) {
        return Response::View(
            '~templates.index', 
            asset('apps/my-profile/index.js')
        );
    }

}